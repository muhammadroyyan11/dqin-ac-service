<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceReport;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ServiceReportController extends Controller
{
    public function index()
    {
        return view('admin.service_reports.index');
    }

    public function data()
    {
        $reports = ServiceReport::with('workOrder', 'technician')->select('service_reports.*');

        return DataTables::of($reports)
            ->addColumn('wo_number', function ($r) {
                return $r->workOrder?->wo_number ?? '-';
            })
            ->addColumn('technician_name', function ($r) {
                return $r->technician?->full_name ?? '-';
            })
            ->addColumn('findings_truncated', function ($r) {
                return strlen($r->findings) > 80
                    ? substr($r->findings, 0, 80) . '...'
                    : ($r->findings ?? '-');
            })
            ->addColumn('action', function ($r) {
                return '<button class="btn btn-sm btn-primary edit-btn" data-id="'.$r->id.'"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="'.$r->id.'"><i class="fa-solid fa-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'work_order_id'  => 'required|exists:work_orders,id',
            'technician_id'  => 'required|exists:technicians,id',
            'findings'       => 'nullable|string',
            'actions_taken'  => 'nullable|string',
            'spareparts_used'=> 'nullable|json',
            'before_photo'   => 'nullable|string|max:255',
            'after_photo'    => 'nullable|string|max:255',
            'customer_notes' => 'nullable|string',
            'customer_signature' => 'nullable|string',
        ]);

        $report = ServiceReport::create($validated);

        return response()->json(['success' => true, 'data' => $report]);
    }

    public function show(ServiceReport $serviceReport)
    {
        $serviceReport->load('workOrder', 'technician');

        return response()->json(['data' => $serviceReport]);
    }

    public function update(Request $request, ServiceReport $serviceReport)
    {
        $validated = $request->validate([
            'work_order_id'  => 'required|exists:work_orders,id',
            'technician_id'  => 'required|exists:technicians,id',
            'findings'       => 'nullable|string',
            'actions_taken'  => 'nullable|string',
            'spareparts_used'=> 'nullable|json',
            'before_photo'   => 'nullable|string|max:255',
            'after_photo'    => 'nullable|string|max:255',
            'customer_notes' => 'nullable|string',
            'customer_signature' => 'nullable|string',
        ]);

        $serviceReport->update($validated);

        return response()->json(['success' => true, 'data' => $serviceReport]);
    }

    public function destroy(ServiceReport $serviceReport)
    {
        $serviceReport->delete();

        return response()->json(['success' => true]);
    }
}
