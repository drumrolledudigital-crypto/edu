<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the student's profile form.
     */
    public function edit()
    {
        return view('student.profile', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update the student's profile information.
     */
    public function update(Request $request, AuditLoggerService $logger)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'mobile_number' => ['required', 'string', 'max:20', 'unique:users,mobile_number,'.$user->id],
            'student_class' => ['required', 'string', 'max:50'],
        ]);

        $oldData = $user->toArray();

        $user->update([
            'name' => $request->name,
            'parent_name' => $request->parent_name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'student_class' => $request->student_class,
        ]);

        $logger->log('Profile', 'Update', "Student profile updated.", $oldData, $user->refresh()->toArray());

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the student's password.
     */
    public function changePassword(Request $request, AuditLoggerService $logger)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $logger->log('Profile', 'PasswordChange', "Student password changed.");

        return back()->with('success', 'Password changed successfully.');
    }
}
