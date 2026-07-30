<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return view('admin.students.index');
    }

    public function list()
    {
        $students = User::where('role', 'student')->latest()->get();
        return response()->json([
            'status' => 'success',
            'data' => $students
        ]);
    }

    public function show($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);

        $totalSessions = \App\Models\Appointment::where('user_id', $student->id)->count();
        $totalPaid = \App\Models\Payment::where('student_id', $student->id)
            ->where('payment_status', 'successful')
            ->sum('amount');

        $appointments = \App\Models\Appointment::where('user_id', $student->id)
            ->with(['subject', 'slot', 'payment'])
            ->latest()
            ->get();

        $invoices = \App\Models\Invoice::where('student_id', $student->id)
            ->with(['appointment.subject', 'payment'])
            ->orderBy('invoice_date', 'desc')
            ->get();

        return view('admin.students.show', compact('student', 'totalSessions', 'totalPaid', 'appointments', 'invoices'));
    }

    public function toggleStatus($id, AuditLoggerService $logger)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $oldStatus = $student->is_active;
        $student->is_active = !$student->is_active;
        $student->save();

        $logger->log('Student', 'ToggleStatus', "Student '{$student->name}' status changed to " . ($student->is_active ? 'active' : 'inactive') . ".", ['is_active' => $oldStatus], ['is_active' => $student->is_active]);

        return response()->json([
            'status' => 'success',
            'message' => 'Student status updated successfully',
            'data' => ['is_active' => $student->is_active]
        ]);
    }

    public function destroy($id, AuditLoggerService $logger)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $oldData = $student->toArray();
        $name = $student->name;
        $student->delete();

        $logger->log('Student', 'Delete', "Student '{$name}' was deleted.", $oldData);

        return response()->json([
            'status' => 'success',
            'message' => 'Student account deleted successfully'
        ]);
    }
}
