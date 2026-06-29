<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ServiceReportController extends Controller
{
    public function index()
    {
        return view('admin.service-reports.index');
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
                return strlen(strip_tags($r->findings ?? '')) > 80
                    ? substr(strip_tags($r->findings), 0, 80) . '...'
                    : ($r->findings ? strip_tags($r->findings) : '-');
            })
            ->addColumn('actions_taken_truncated', function ($r) {
                return strlen(strip_tags($r->actions_taken ?? '')) > 80
                    ? substr(strip_tags($r->actions_taken), 0, 80) . '...'
                    : ($r->actions_taken ? strip_tags($r->actions_taken) : '-');
            })
            ->addColumn('spareparts_summary', function ($r) {
                $spareparts = $r->spareparts_used;
                if (!$spareparts || !is_array($spareparts) || count($spareparts) === 0) {
                    return '-';
                }
                $labels = array_map(function ($sp) {
                    $name = $sp['name'] ?? 'Item #' . ($sp['id'] ?? '');
                    $qty = $sp['qty'] ?? 1;
                    return $name . ' x' . $qty;
                }, $spareparts);
                return implode(', ', $labels);
            })
            ->addColumn('action', function ($r) {
                return '<button class="btn btn-sm btn-primary edit-btn" data-id="'.$r->id.'"><i class="fa-solid fa-pen"></i></button>
                        <a href="'.route('admin.service-reports.pdf', $r->id).'" class="btn btn-sm btn-info"><i class="fa-solid fa-file-pdf"></i></a>
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
        ]);

        $report = ServiceReport::create($validated);

        return response()->json(['success' => true, 'data' => $report->load('workOrder', 'technician')]);
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
        ]);

        $serviceReport->update($validated);

        return response()->json(['success' => true, 'data' => $serviceReport->load('workOrder', 'technician')]);
    }

    public function generatePdf(ServiceReport $serviceReport)
    {
        $serviceReport->load('workOrder.customer', 'technician');
        $pdf = Pdf::loadView('admin.service-reports.pdf', ['report' => $serviceReport]);
        return $pdf->download('service-report-'.$serviceReport->id.'.pdf');
    }

    public function destroy(ServiceReport $serviceReport)
    {
        $serviceReport->delete();

        return response()->json(['success' => true]);
    }
}
