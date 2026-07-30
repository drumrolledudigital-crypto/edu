<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    public function index()
    {
        $roles = Role::where('slug', '!=', 'super-admin')->get();
        return view('admin.staff.index', compact('roles'));
    }

    public function list()
    {
        // Get all users who are not students
        $staff = User::where('role', '!=', 'student')
            ->with('roles')
            ->get();
            
        return response()->json([
            'status' => 'success',
            'data' => $staff
        ]);
    }

    public function store(Request $request, AuditLoggerService $logger)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', Password::defaults()],
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($request->role_id);
        if ($role->slug === 'super-admin') {
            return response()->json(['status' => 'error', 'message' => 'Cannot assign Super Admin role.'], 403);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'is_active' => true,
        ]);

        $user->roles()->sync([$request->role_id]);
        
        $logger->log('Staff', 'Create', "New staff member '{$user->name}' was created.", null, $user->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Staff member created successfully',
            'data' => $user
        ]);
    }

    public function show(User $user)
    {
        if ($user->role === 'student') {
            abort(404);
        }
        $user->load('roles');
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function update(Request $request, User $user, AuditLoggerService $logger)
    {
        if ($user->role === 'student') {
            abort(404);
        }
        if ($user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
             return response()->json(['status' => 'error', 'message' => 'Unauthorized to modify Super Admin.'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', Password::defaults()],
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'required|boolean'
        ]);

        $role = Role::findOrFail($request->role_id);
        if ($role->slug === 'super-admin' && !$user->hasRole('super-admin')) {
            return response()->json(['status' => 'error', 'message' => 'Cannot assign Super Admin role.'], 403);
        }

        $oldData = $user->toArray();
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->is_active,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->roles()->sync([$request->role_id]);
        
        $logger->log('Staff', 'Update', "Staff member '{$user->name}' was updated.", $oldData, $user->refresh()->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Staff member updated successfully',
            'data' => $user
        ]);
    }

    public function destroy(User $user, AuditLoggerService $logger)
    {
        if ($user->role === 'student') {
            abort(404);
        }
        if ($user->hasRole('super-admin')) {
            return response()->json(['status' => 'error', 'message' => 'Super Admin cannot be deleted.'], 403);
        }

        $oldData = $user->toArray();
        $name = $user->name;
        $user->delete();
        
        $logger->log('Staff', 'Delete', "Staff member '{$name}' was deleted.", $oldData);

        return response()->json([
            'status' => 'success',
            'message' => 'Staff member deleted successfully'
        ]);
    }
}
