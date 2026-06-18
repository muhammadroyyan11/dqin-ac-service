<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\WorkOrderProgressLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $technician = $user->technician;

        if (!$technician) {
            $workOrders = collect();
        } else {
            $workOrders = WorkOrder::whereHas('technicians', function ($q) use ($technician) {
                $q->where('technician_id', $technician->id);
            })->with(['customer', 'technicians'])->orderBy('created_at', 'desc')->get();
        }

        return view('technician.dashboard', compact('workOrders', 'technician'));
    }

    public function show(WorkOrder $workOrder)
    {
        $user = Auth::user();
        $technician = $user->technician;

        if (!$technician || !$workOrder->technicians->contains($technician->id)) {
            abort(403, 'You are not assigned to this work order.');
        }

        $workOrder->load('customer', 'customerUnit', 'technicians');
        $myPivot = $workOrder->technicians->find($technician->id)->pivot;

        return view('technician.work-order-detail', compact('workOrder', 'technician', 'myPivot'));
    }

    public function updateProgress(Request $request, WorkOrder $workOrder)
    {
        $user = Auth::user();
        $technician = $user->technician;

        if (!$technician || !$workOrder->technicians->contains($technician->id)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'note' => 'required|string',
        ]);

        $updateData = [
            'status' => 'in_progress',
        ];

        if ($request->has('note')) {
            $updateData['progress_note'] = $request->note;
        }

        $workOrder->technicians()->updateExistingPivot($technician->id, $updateData);

        WorkOrderProgressLog::create([
            'work_order_id' => $workOrder->id,
            'technician_id' => $technician->id,
            'user_id' => $user->id,
            'note' => $request->note,
        ]);

        if ($workOrder->status === 'pending') {
            $workOrder->update(['status' => 'in_progress']);
        }

        return response()->json(['success' => true]);
    }
}
