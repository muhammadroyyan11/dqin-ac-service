<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.customers.index');
    }

    public function data()
    {
        $customers = Customer::select(['id', 'full_name', 'phone', 'email', 'city', 'is_active', 'created_at']);

        return DataTables::of($customers)
            ->addColumn('action', function ($customer) {
                return '<button class="edit-btn" data-id="'.$customer->id.'">Edit</button>
                        <button class="delete-btn" data-id="'.$customer->id.'">Delete</button>';
            })
            ->editColumn('is_active', function ($customer) {
                return $customer->is_active
                    ? '<span class="badge-active">Active</span>'
                    : '<span class="badge-inactive">Inactive</span>';
            })
            ->rawColumns(['action', 'is_active'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'address'   => 'nullable|string',
            'city'      => 'nullable|string|max:100',
            'notes'     => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $customer = Customer::create($validated);

        return response()->json(['success' => true, 'data' => $customer]);
    }

    public function show(Customer $customer)
    {
        return response()->json(['data' => $customer]);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'address'   => 'nullable|string',
            'city'      => 'nullable|string|max:100',
            'notes'     => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $customer->update($validated);

        return response()->json(['success' => true, 'data' => $customer]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json(['success' => true]);
    }
}
