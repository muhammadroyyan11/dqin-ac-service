<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
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
            'status' => 'required|string|in:assigned,in_progress,completed',
            'progress_note' => 'nullable|string',
        ]);

        $updateData = [
            'status' => $request->status,
        ];

        if ($request->has('progress_note')) {
            $updateData['progress_note'] = $request->progress_note;
        }

        if ($request->status === 'completed') {
            $updateData['completed_at'] = now();
        }

        $workOrder->technicians()->updateExistingPivot($technician->id, $updateData);

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
}
