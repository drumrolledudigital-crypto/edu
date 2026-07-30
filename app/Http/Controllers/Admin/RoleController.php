<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $permissions = Permission::all()->groupBy('group');
        return view('admin.roles.index', compact('permissions'));
    }

    public function list()
    {
        $roles = Role::withCount('permissions', 'users')->get();
        return response()->json([
            'status' => 'success',
            'data' => $roles
        ]);
    }

    public function store(Request $request, AuditLoggerService $logger)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        $role->permissions()->sync($request->permissions);
        
        $logger->log('Role', 'Create', "Role '{$role->name}' was created with " . count($request->permissions) . " permissions.", null, $role->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully',
            'data' => $role
        ]);
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        return response()->json([
            'status' => 'success',
            'data' => $role
        ]);
    }

    public function update(Request $request, Role $role, AuditLoggerService $logger)
    {
        if ($role->slug === 'super-admin') {
            return response()->json(['status' => 'error', 'message' => 'Super Admin role cannot be modified.'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $oldData = $role->toArray();
        
        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        $role->permissions()->sync($request->permissions);
        
        $logger->log('Role', 'Update', "Role '{$role->name}' was updated.", $oldData, $role->refresh()->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully',
            'data' => $role
        ]);
    }

    public function destroy(Role $role, AuditLoggerService $logger)
    {
        if ($role->slug === 'super-admin') {
            return response()->json(['status' => 'error', 'message' => 'Super Admin role cannot be deleted.'], 403);
        }

        if ($role->users()->count() > 0) {
            return response()->json(['status' => 'error', 'message' => 'Cannot delete role assigned to active users.'], 403);
        }

        $oldData = $role->toArray();
        $name = $role->name;
        $role->delete();
        
        $logger->log('Role', 'Delete', "Role '{$name}' was deleted.", $oldData);

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully'
        ]);
    }
}
