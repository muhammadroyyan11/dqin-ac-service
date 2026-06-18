<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TechnicianController extends Controller
{
    public function index()
    {
        return view('admin.technicians.index');
    }

    public function data()
    {
        $technicians = Technician::select(['id', 'nik', 'full_name', 'phone', 'specialization', 'is_active', 'created_at']);

        return DataTables::of($technicians)
            ->addColumn('action', function ($technician) {
                return '<button class="edit-btn" data-id="'.$technician->id.'">Edit</button>
                        <button class="delete-btn" data-id="'.$technician->id.'">Delete</button>';
            })
            ->editColumn('is_active', function ($technician) {
                return $technician->is_active
                    ? '<span class="badge-active">Active</span>'
                    : '<span class="badge-inactive">Inactive</span>';
            })
            ->rawColumns(['action', 'is_active'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik'             => 'nullable|string|max:50',
            'full_name'       => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string',
            'specialization'  => 'nullable|string|max:100',
            'is_active'       => 'boolean',
        ]);

        $technician = Technician::create($validated);

        return response()->json(['success' => true, 'data' => $technician]);
    }

    public function show(Technician $technician)
    {
        return response()->json(['data' => $technician]);
    }

    public function update(Request $request, Technician $technician)
    {
        $validated = $request->validate([
            'nik'             => 'nullable|string|max:50',
            'full_name'       => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string',
            'specialization'  => 'nullable|string|max:100',
            'is_active'       => 'boolean',
        ]);

        $technician->update($validated);

        return response()->json(['success' => true, 'data' => $technician]);
    }

    public function destroy(Technician $technician)
    {
        $technician->delete();

        return response()->json(['success' => true]);
    }
}
