<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ComplaintController extends Controller
{
    public function index()
    {
        return view('admin.complaints.index');
    }

    public function data()
    {
        $complaints = Complaint::with('customer')->select('complaints.*');

        return DataTables::of($complaints)
            ->addColumn('customer_name', function ($c) {
                return $c->customer?->full_name ?? '-';
            })
            ->addColumn('action', function ($c) {
                return '<button class="edit-btn" data-id="'.$c->id.'">Edit</button>
                        <button class="delete-btn" data-id="'.$c->id.'">Delete</button>';
            })
            ->editColumn('created_at', function ($c) {
                return $c->created_at ? $c->created_at->format('Y-m-d H:i') : '-';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'  => 'required|exists:customers,id',
            'work_order_id'=> 'nullable|exists:work_orders,id',
            'subject'      => 'required|string|max:255',
            'description'  => 'nullable|string',
            'status'       => 'required|string|max:50',
            'priority'     => 'required|string|max:50',
            'notes'        => 'nullable|string',
        ]);

        $complaint = Complaint::create($validated);

        return response()->json(['success' => true, 'data' => $complaint]);
    }

    public function show(Complaint $complaint)
    {
        $complaint->load('customer', 'workOrder');

        return response()->json(['data' => $complaint]);
    }

    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'customer_id'  => 'required|exists:customers,id',
            'work_order_id'=> 'nullable|exists:work_orders,id',
            'subject'      => 'required|string|max:255',
            'description'  => 'nullable|string',
            'status'       => 'required|string|max:50',
            'priority'     => 'required|string|max:50',
            'notes'        => 'nullable|string',
        ]);

        $complaint->update($validated);

        return response()->json(['success' => true, 'data' => $complaint]);
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();

        return response()->json(['success' => true]);
    }
}
