<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceContract;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MaintenanceContractController extends Controller
{
    public function index()
    {
        return view('admin.maintenance_contracts.index');
    }

    public function data()
    {
        $contracts = MaintenanceContract::with('customer')->select('maintenance_contracts.*');

        return DataTables::of($contracts)
            ->addColumn('customer_name', function ($c) {
                return $c->customer?->full_name ?? '-';
            })
            ->addColumn('action', function ($c) {
                return '<button class="btn btn-sm btn-primary edit-btn" data-id="'.$c->id.'"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="'.$c->id.'"><i class="fa-solid fa-trash"></i></button>';
            })
            ->editColumn('price', function ($c) {
                return number_format($c->price, 2);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'visit_frequency'=> 'required|string|max:100',
            'service_type'   => 'nullable|string|max:255',
            'price'          => 'required|numeric|min:0',
            'status'         => 'required|string|max:50',
            'notes'          => 'nullable|string',
        ]);

        $contract = MaintenanceContract::create($validated);

        return response()->json(['success' => true, 'data' => $contract]);
    }

    public function show(MaintenanceContract $maintenanceContract)
    {
        $maintenanceContract->load('customer');

        return response()->json(['data' => $maintenanceContract]);
    }

    public function update(Request $request, MaintenanceContract $maintenanceContract)
    {
        $validated = $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'visit_frequency'=> 'required|string|max:100',
            'service_type'   => 'nullable|string|max:255',
            'price'          => 'required|numeric|min:0',
            'status'         => 'required|string|max:50',
            'notes'          => 'nullable|string',
        ]);

        $maintenanceContract->update($validated);

        return response()->json(['success' => true, 'data' => $maintenanceContract]);
    }

    public function destroy(MaintenanceContract $maintenanceContract)
    {
        $maintenanceContract->delete();

        return response()->json(['success' => true]);
    }
}
