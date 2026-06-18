<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index()
    {
        return view('admin.permissions.index');
    }

    public function data()
    {
        $permissions = Permission::select(['id', 'name', 'display_name', 'group', 'created_at']);

        return DataTables::of($permissions)
            ->addColumn('action', function ($perm) {
                return '
                    <button class="btn btn-sm btn-primary edit-btn" data-id="'.$perm->id.'"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="'.$perm->id.'"><i class="fa-solid fa-trash"></i></button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:permissions,name',
            'display_name' => 'required|string|max:100',
            'group' => 'required|string|max:50',
        ]);

        $permission = Permission::create($validated);

        return response()->json(['success' => true, 'data' => $permission]);
    }

    public function show(Permission $permission)
    {
        return response()->json(['data' => $permission]);
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:permissions,name,'.$permission->id,
            'display_name' => 'required|string|max:100',
            'group' => 'required|string|max:50',
        ]);

        $permission->update($validated);

        return response()->json(['success' => true, 'data' => $permission]);
    }

    public function destroy(Permission $permission)
    {
        $permission->roles()->detach();
        $permission->delete();

        return response()->json(['success' => true]);
    }
}
