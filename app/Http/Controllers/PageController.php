<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Book;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the landing page.
     */
    public function home()
    {
        $subjects = Subject::where('status', 'active')->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->take(6)->get();

        $totalStudents = \App\Models\User::where('role', 'student')->count();
        $completedSessions = \App\Models\Appointment::where('status', 'completed')->count();
        $totalAppointments = \App\Models\Appointment::count();
        $successRate = $totalAppointments > 0 ? round(($completedSessions / $totalAppointments) * 100) : 98;

        $stats = [
            'students' => $totalStudents > 0 ? $totalStudents : 500,
            'sessions' => $completedSessions > 0 ? $completedSessions : 1000,
            'success_rate' => $successRate,
        ];

        return view('home', compact('subjects', 'stats'));
    }

    /**
     * Display the About page.
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Display the Contact page.
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission.
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'parent_name'  => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'required|string|max:20',
            'grade'        => 'nullable|string|max:50',
            'subject'      => 'nullable|string|max:255',
            'message'      => 'required|string|max:2000',
        ]);

        $adminEmail = \App\Models\Setting::get('contact_email', 'hello@drumroll.com');
        $adminName  = \App\Models\Setting::get('platform_name', 'Drumroll');

        $body = "New contact form submission\n\n";
        $body .= "Student Name: {$validated['student_name']}\n";
        $body .= "Parent Name: {$validated['parent_name']}\n";
        $body .= "Email: {$validated['email']}\n";
        $body .= "Phone: {$validated['phone']}\n";
        if (!empty($validated['grade'])) {
            $body .= "Grade: {$validated['grade']}\n";
        }
        if (!empty($validated['subject'])) {
            $body .= "Subject: {$validated['subject']}\n";
        }
        $body .= "\nMessage:\n{$validated['message']}";

        \Illuminate\Support\Facades\Mail::raw(
            $body,
            function ($mail) use ($adminEmail, $adminName, $validated) {
                $mail->to($adminEmail, $adminName)
                    ->subject("Contact Form: {$validated['parent_name']} - {$validated['student_name']}")
                    ->replyTo($validated['email'], $validated['parent_name']);
            }
        );

        return response()->json([
            'success' => true,
            'message' => "Thank you, {$validated['parent_name']}! Your message has been sent. We'll get back to you within 24 hours.",
        ]);
    }

    /**
     * Display the FAQ page.
     */
    public function faq()
    {
        return view('faq');
    }

    public function privacyPolicy()
    {
        return view('privacy-policy');
    }

    public function termsConditions()
    {
        return view('terms-conditions');
    }

    public function refundPolicy()
    {
        return view('refund-policy');
    }

    public function cancellationPolicy()
    {
        return view('cancellation-policy');
    }

    /**
     * Display the Student Registration page.
     */
    public function register()
    {
        return view('student.register');
    }

    /**
     * Display the Student Login page.
     */
    public function login()
    {
        return view('student.login');
    }

    /**
     * Display the Subjects Listing page.
     */
    public function subjects()
    {
        $subjects = Subject::where('status', 'active')->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();
        return view('subjects.index', compact('subjects'));
    }

    /**
     * Display the Books Listing page.
     */
    public function books()
    {
        $books = Book::with('subject')->where('status', 'active')->orderBy('created_at', 'desc')->get();
        return view('books.index', compact('books'));
    }

    /**
     * Display a single Book detail page.
     */
    public function bookDetail($slug)
    {
        $book = Book::with('subject')->where('slug', $slug)->where('status', 'active')->firstOrFail();
        return view('books.show', compact('book'));
    }

    /**
     * Display the Doubt Submission Form.
     */
    public function doubts()
    {
        $subjects = Subject::where('status', 'active')->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->get();
        return view('doubts.create', compact('subjects'));
    }

    /**
     * Redirect to the Student Booking flow.
     */
    public function booking()
    {
        return redirect()->route('student.booking.create');
    }
}

