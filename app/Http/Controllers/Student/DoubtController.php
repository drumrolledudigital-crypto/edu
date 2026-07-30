<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doubt;
use App\Models\Subject;
use App\Services\IntegrationAutomationService;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DoubtController extends Controller
{
    /**
     * Display a listing of the student's doubts.
     */
    public function index()
    {
        $doubts = Doubt::where('user_id', Auth::id())
            ->with('subject')
            ->latest()
            ->paginate(10);

        return view('student.doubts.index', compact('doubts'));
    }

    /**
     * Store a newly created doubt in storage.
     */
    public function store(Request $request, IntegrationAutomationService $automation, AuditLoggerService $logger)
    {
        $hasBooking = Appointment::where('user_id', Auth::id())->exists();
        if (!$hasBooking) {
            return redirect()->route('student.booking.create')
                ->withErrors(['booking' => 'You must book a session before submitting a doubt. Please book a session first.']);
        }

        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'topic_name' => 'required|string|max:255',
            'doubt_titles' => 'required|array|min:1',
            'doubt_titles.*' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('doubts', 'public');
        }

        $doubt = Doubt::create([
            'user_id' => Auth::id(),
            'subject_id' => $request->subject_id,
            'topic_name' => $request->topic_name,
            'title' => $request->doubt_titles,
            'description' => $request->description,
            'attachment' => $attachmentPath,
            'status' => 'pending',
        ]);

        try {
            $automation->createInternalAdminNotification(
                'Doubt',
                'New Doubt Submitted',
                Auth::user()->name . " submitted a doubt on {$doubt->topic_name}.",
                $doubt,
                Auth::user(),
                route('admin.doubts.show', $doubt->id),
                'help-circle'
            );
        } catch (\Throwable $th) {}

        $logger->log('Doubt', 'Create', "Student '" . Auth::user()->name . "' submitted a doubt.", null, $doubt->toArray());

        return redirect()->route('student.doubts.index')->with('success', 'Your doubt has been submitted successfully.');
    }

    /**
     * Display the specified doubt.
     */
    public function show(Doubt $doubt)
    {
        // Ensure student can only view their own doubt
        if ($doubt->user_id !== Auth::id()) {
            abort(403);
        }

        $doubt->load('subject', 'appointment');

        return view('student.doubts.show', compact('doubt'));
    }
}
