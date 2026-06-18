<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\User;
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
        $technicians = Technician::select(['id', 'identity', 'full_name', 'phone', 'specialization', 'start_date', 'is_active', 'created_at']);

        return DataTables::of($technicians)
            ->addColumn('action', function ($technician) {
                return '<button class="btn btn-sm btn-primary edit-btn" data-id="'.$technician->id.'"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="'.$technician->id.'"><i class="fa-solid fa-trash"></i></button>';
            })
            ->editColumn('is_active', function ($technician) {
                return $technician->is_active
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-secondary">Inactive</span>';
            })
            ->rawColumns(['action', 'is_active'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'         => 'nullable|exists:users,id',
            'identity'        => 'nullable|string|max:50|unique:technicians,identity',
            'full_name'       => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string',
            'specialization'  => 'nullable|string|max:100',
            'start_date'      => 'nullable|date',
            'is_active'       => 'boolean',
            'email'           => 'nullable|email|max:255|unique:users,email',
            'password'        => 'nullable|string|min:6',
        ]);

        if ($request->filled('user_id')) {
            $user = User::findOrFail($request->user_id);
        } elseif ($request->filled('email')) {
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => bcrypt($request->password ?? 'password'),
                'phone' => $request->phone,
            ]);
            $role = \App\Models\Role::where('name', 'teknisi')->first();
            if ($role) $user->roles()->attach($role);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Select existing user or provide email to create new account.'
            ], 422);
        }

        $technician = Technician::create([
            'user_id' => $user->id,
            'identity' => $request->identity ?? 'TEC-' . str_pad(Technician::max('id') + 1, 3, '0', STR_PAD_LEFT),
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'specialization' => $request->specialization,
            'start_date' => $request->start_date,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $technician->load('user');

        return response()->json(['success' => true, 'data' => $technician]);
    }

    public function show(Technician $technician)
    {
        $technician->load('user');
        return response()->json(['data' => $technician]);
    }

    public function update(Request $request, Technician $technician)
    {
        $validated = $request->validate([
            'identity'        => 'nullable|string|max:50|unique:technicians,identity,'.$technician->id,
            'full_name'       => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string',
            'specialization'  => 'nullable|string|max:100',
            'start_date'      => 'nullable|date',
            'is_active'       => 'boolean',
        ]);

        $technician->update($validated);

        return response()->json(['success' => true, 'data' => $technician]);
    }

    public function destroy(Technician $technician)
    {
        $user = $technician->user;
        $technician->delete();
        if ($user && !$user->technician && $user->hasRole('teknisi')) {
            $user->delete();
        }
        return response()->json(['success' => true]);
    }
}
