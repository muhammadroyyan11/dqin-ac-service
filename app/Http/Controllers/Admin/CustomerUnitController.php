<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerUnit;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerUnitController extends Controller
{
    public function index()
    {
        return view('admin.customer_units.index');
    }

    public function data()
    {
        $units = CustomerUnit::with('customer')->select('customer_units.*');
        return DataTables::of($units)
            ->addColumn('customer_name', fn($u) => $u->customer?->full_name ?? '-')
            ->addColumn('action', fn($u) => '
                <button onclick="editUnit('.$u->id.')" class="btn btn-sm btn-primary"><i class="fa-solid fa-pen"></i></button>
                <button onclick="deleteUnit('.$u->id.')" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
            ')
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'customer_id' => 'required|exists:customers,id',
            'brand' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'pk' => 'required|numeric',
            'serial_number' => 'nullable|string|max:255',
            'installation_location' => 'nullable|string|max:255',
        ]);
        return response()->json(CustomerUnit::create($data));
    }

    public function show($id) {
        return response()->json(CustomerUnit::findOrFail($id));
    }

    public function update(Request $r, $id)
    {
        $unit = CustomerUnit::findOrFail($id);
        $data = $r->validate([
            'customer_id' => 'required|exists:customers,id',
            'brand' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'pk' => 'required|numeric',
            'serial_number' => 'nullable|string|max:255',
            'installation_location' => 'nullable|string|max:255',
        ]);
        $unit->update($data);
        return response()->json($unit);
    }

    public function byCustomer($customerId)
    {
        $units = CustomerUnit::where('customer_id', $customerId)->get();
        return response()->json($units);
    }

    public function destroy($id)
    {
        CustomerUnit::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
