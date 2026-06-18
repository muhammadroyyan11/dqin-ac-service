<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\Technician;
use App\Models\WorkOrderProgressLog;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WorkOrderController extends Controller
{
    public function index()
    {
        return view('admin.work-orders.index');
    }

    public function data()
    {
        $workOrders = WorkOrder::with('customer', 'technicians')->select([
            'id', 'wo_number', 'customer_id', 'service_type', 'status', 'priority', 'scheduled_date', 'created_at'
        ]);

        return DataTables::of($workOrders)
            ->addColumn('customer_name', function ($workOrder) {
                return $workOrder->customer ? $workOrder->customer->full_name : '-';
            })
            ->addColumn('technicians_list', function ($workOrder) {
                return $workOrder->technicians->map(function ($t) {
                    $badge = $t->pivot->is_captain
                        ? '<span class="badge badge-captain">Captain</span>'
                        : '';
                    return '<span class="tech-badge">' . e($t->full_name) . ' ' . $badge . '</span>';
                })->implode(' ');
            })
            ->addColumn('action', function ($workOrder) {
                $detailUrl = route('admin.work-orders.detail', $workOrder->id);
                return '
                    <a href="'.$detailUrl.'" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i></a>
                    <button class="btn btn-sm btn-primary edit-btn" data-id="'.$workOrder->id.'"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="'.$workOrder->id.'"><i class="fa-solid fa-trash"></i></button>
                ';
            })
            ->rawColumns(['action', 'technicians_list'])
            ->make(true);
    }

    public function detail(WorkOrder $workOrder)
    {
        $workOrder->load('customer.customerUnits', 'customerUnit', 'technicians', 'serviceReports', 'progressLogs.technician', 'progressLogs.user');
        return view('admin.work-orders.detail', compact('workOrder'));
    }

    public function technicians(WorkOrder $workOrder)
    {
        $workOrder->load('technicians');
        return response()->json(['data' => $workOrder->technicians]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wo_number'       => 'nullable|string|max:50|unique:work_orders,wo_number',
            'customer_id'     => 'required|exists:customers,id',
            'customer_unit_id' => 'nullable|exists:customer_units,id',
            'service_type'    => 'required|string|max:100',
            'description'     => 'nullable|string',
            'status'          => 'required|string|in:pending,in_progress,completed,cancelled',
            'priority'        => 'required|string|in:low,normal,high,emergency',
            'scheduled_date'  => 'nullable|date',
            'notes'           => 'nullable|string',
            'total_estimate'  => 'nullable|numeric',
            'technicians'     => 'nullable|array',
            'technicians.*.id' => 'required|integer|exists:technicians,id',
            'technicians.*.is_captain' => 'required|in:true,false,1,0',
        ]);

        if (empty($validated['wo_number'])) {
            $validated['wo_number'] = 'WO-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        }

        $workOrder = WorkOrder::create($validated);

        if ($request->has('technicians')) {
            $syncData = [];
            foreach ($request->technicians as $tech) {
                $syncData[$tech['id']] = [
                    'is_captain' => filter_var($tech['is_captain'], FILTER_VALIDATE_BOOLEAN),
                    'status' => 'assigned',
                ];
                WorkOrderProgressLog::create([
                    'work_order_id' => $workOrder->id,
                    'technician_id' => $tech['id'],
                    'user_id' => auth()->id(),
                    'status' => 'assigned',
                    'note' => 'Technician assigned',
                ]);
            }
            $workOrder->technicians()->sync($syncData);
        }

        return response()->json(['success' => true, 'data' => $workOrder->load('technicians')]);
    }

    public function show(WorkOrder $workOrder)
    {
        $workOrder->load('customer', 'technicians');
        return response()->json(['data' => $workOrder]);
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $validated = $request->validate([
            'wo_number'       => 'required|string|max:50|unique:work_orders,wo_number,'.$workOrder->id,
            'customer_id'     => 'required|exists:customers,id',
            'customer_unit_id' => 'nullable|exists:customer_units,id',
            'service_type'    => 'required|string|max:100',
            'description'     => 'nullable|string',
            'status'          => 'required|string|in:pending,in_progress,completed,cancelled',
            'priority'        => 'required|string|in:low,normal,high,emergency',
            'scheduled_date'  => 'nullable|date',
            'completed_date'  => 'nullable|date',
            'notes'           => 'nullable|string',
            'total_estimate'  => 'nullable|numeric',
            'technicians'     => 'nullable|array',
            'technicians.*.id' => 'required|integer|exists:technicians,id',
            'technicians.*.is_captain' => 'required|in:true,false,1,0',
        ]);

        $workOrder->update($validated);

        if ($request->has('technicians')) {
            $existingPivots = $workOrder->technicians()->get()->keyBy('id');
            $syncData = [];
            foreach ($request->technicians as $tech) {
                $existing = $existingPivots->get($tech['id']);
                $syncData[$tech['id']] = [
                    'is_captain' => filter_var($tech['is_captain'], FILTER_VALIDATE_BOOLEAN),
                    'status' => $existing ? $existing->pivot->status : 'assigned',
                ];
            }
            $workOrder->technicians()->sync($syncData);
        }

        return response()->json(['success' => true, 'data' => $workOrder->load('technicians')]);
    }

    public function updateProgress(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'technician_id' => 'required|exists:technicians,id',
            'status' => 'required|string|in:assigned,in_progress,completed',
            'progress_note' => 'nullable|string',
        ]);

        $pivot = $workOrder->technicians()
            ->where('technician_id', $request->technician_id)
            ->first();

        if (!$pivot) {
            return response()->json(['success' => false, 'message' => 'Technician not assigned to this work order.'], 404);
        }

        $updateData = [
            'status' => $request->status,
        ];

        if ($request->has('progress_note')) {
            $updateData['progress_note'] = $request->progress_note;
        }

        if ($request->status === 'completed') {
            $updateData['completed_at'] = now();
        }

        $workOrder->technicians()->updateExistingPivot($request->technician_id, $updateData);

        WorkOrderProgressLog::create([
            'work_order_id' => $workOrder->id,
            'technician_id' => $request->technician_id,
            'user_id' => auth()->id(),
            'status' => $request->status,
            'note' => $request->progress_note,
        ]);

        $allCompleted = $workOrder->technicians()
            ->wherePivot('status', '!=', 'completed')
            ->count() === 0;

        if ($allCompleted && $workOrder->technicians()->count() > 0) {
            $workOrder->update(['status' => 'completed', 'completed_date' => now()]);
        } elseif ($request->status === 'in_progress' && $workOrder->status === 'pending') {
            $workOrder->update(['status' => 'in_progress']);
        }

        return response()->json(['success' => true]);
    }

    public function complete(Request $request, WorkOrder $workOrder)
    {
        $workOrder->update([
            'status' => 'completed',
            'completed_date' => now(),
        ]);

        $techIds = $workOrder->technicians()->pluck('technician_id')->toArray();

        $workOrder->technicians()->updateExistingPivot(
            $techIds,
            ['status' => 'completed', 'completed_at' => now()]
        );

        foreach ($techIds as $techId) {
            WorkOrderProgressLog::create([
                'work_order_id' => $workOrder->id,
                'technician_id' => $techId,
                'user_id' => auth()->id(),
                'status' => 'completed',
                'note' => 'Work order completed',
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function destroy(WorkOrder $workOrder)
    {
        $workOrder->technicians()->detach();
        $workOrder->delete();
        return response()->json(['success' => true]);
    }
}
