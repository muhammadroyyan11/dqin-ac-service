<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        $permissions = Permission::all()->groupBy('group');
        return view('admin.roles.index', compact('permissions'));
    }

    public function data()
    {
        $roles = Role::withCount('users', 'permissions');

        return DataTables::of($roles)
            ->addColumn('action', function ($role) {
                if ($role->name === 'super_admin') return '<span class="badge badge-secondary">Protected</span>';
                return '
                    <button class="btn btn-sm btn-primary edit-btn" data-id="'.$role->id.'"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn btn-sm btn-info perm-btn" data-id="'.$role->id.'" data-name="'.e($role->display_name).'"><i class="fa-solid fa-shield"></i></button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="'.$role->id.'"><i class="fa-solid fa-trash"></i></button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $role = Role::create($validated);

        return response()->json(['success' => true, 'data' => $role]);
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        return response()->json(['data' => $role]);
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Super Admin role cannot be edited.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,'.$role->id,
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $role->update($validated);

        return response()->json(['success' => true, 'data' => $role]);
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Super Admin role cannot be deleted.'], 403);
        }

        $role->permissions()->detach();
        $role->users()->detach();
        $role->delete();

        return response()->json(['success' => true]);
    }

    public function permissions(Role $role)
    {
        $role->load('permissions');
        $allPermissions = Permission::all()->groupBy('group');
        return response()->json([
            'role' => $role,
            'permissions' => $allPermissions,
            'rolePermissionIds' => $role->permissions->pluck('id'),
        ]);
    }

    public function updatePermissions(Request $request, Role $role)
    {
        if ($role->name === 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Super Admin already has all permissions.'], 403);
        }

        $request->validate([
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->sync($request->permission_ids ?? []);

        // Flush cache for all users with this role
        foreach ($role->users as $user) {
            $user->flushPermissionsCache();
        }

        return response()->json(['success' => true]);
    }
}
