<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\SlotController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\DoubtController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\RefundController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BookPurchaseController;

use App\Http\Controllers\PageController;

use App\Http\Controllers\Student\AuthController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\PasswordResetController;
use App\Http\Controllers\Student\DoubtController as StudentDoubtController;
use App\Http\Controllers\Student\BookingController;
use App\Http\Controllers\Student\CartController;
use App\Http\Controllers\Student\PaymentController as StudentPaymentController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit')->middleware('throttle:5,1');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/terms-conditions', [PageController::class, 'termsConditions'])->name('terms.conditions');
Route::get('/refund-policy', [PageController::class, 'refundPolicy'])->name('refund.policy');
Route::get('/cancellation-policy', [PageController::class, 'cancellationPolicy'])->name('cancellation.policy');

Route::prefix('student')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/register', [PageController::class, 'register'])->name('student.register');
        Route::post('/register', [AuthController::class, 'registerPost'])->middleware('throttle:10,1')->name('student.register.post');
        Route::get('/login', [PageController::class, 'login'])->name('login');
        Route::post('/login', [AuthController::class, 'loginPost'])->middleware('throttle:10,1')->name('login.post');
        
        Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('student.password.request');
        Route::post('/forgot-password', [PasswordResetController::class, 'store'])->middleware('throttle:5,1')->name('student.password.email');
        Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('student.password.reset');
        Route::post('/reset-password', [PasswordResetController::class, 'update'])->middleware('throttle:5,1')->name('student.password.update');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('student.logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('student.profile');
        Route::post('/profile', [ProfileController::class, 'update'])->middleware('throttle:30,1')->name('student.profile.update');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->middleware('throttle:10,1')->name('student.profile.password');

        // Doubt Management
        Route::get('/doubts', [StudentDoubtController::class, 'index'])->name('student.doubts.index');
        Route::get('/doubts/{doubt}', [StudentDoubtController::class, 'show'])->name('student.doubts.show');
        Route::get('/submit-doubt', [PageController::class, 'doubts'])->name('doubts.create');
        Route::post('/submit-doubt', [StudentDoubtController::class, 'store'])->middleware('throttle:10,1')->name('student.doubts.store');

        // Session Booking
        Route::get('/book-session', [BookingController::class, 'create'])->name('student.booking.create');
        Route::post('/book-session', [BookingController::class, 'store'])->name('student.booking.store');
        Route::get('/my-bookings', [BookingController::class, 'index'])->name('student.booking.index');
        Route::get('/upcoming-sessions', [BookingController::class, 'upcoming'])->name('student.sessions.upcoming');
        Route::get('/past-sessions', [BookingController::class, 'past'])->name('student.sessions.past');

        // Stripe Payments
        Route::get('/payment/pay/{appointment}', [StudentPaymentController::class, 'show'])->name('student.payment.pay');
        Route::post('/payment/checkout/{appointment}', [StudentPaymentController::class, 'checkout'])->name('student.payment.checkout');
        Route::get('/payment/success', [StudentPaymentController::class, 'success'])->name('student.payment.success');
        Route::get('/payments/failed/{appointment}', [StudentPaymentController::class, 'failed'])->name('student.payment.failed');
        Route::get('/payments/history', [StudentPaymentController::class, 'history'])->name('student.payments.history');

        // Invoices
        Route::get('/invoices', [\App\Http\Controllers\Student\InvoiceController::class, 'index'])->name('student.invoices.index');
        Route::get('/invoices/{invoice}', [\App\Http\Controllers\Student\InvoiceController::class, 'show'])->name('student.invoices.show');
        Route::get('/invoices/{id}/download', [\App\Http\Controllers\Student\InvoiceController::class, 'download'])->name('student.invoices.download');
        Route::get('/invoices/{id}/print', [\App\Http\Controllers\Student\InvoiceController::class, 'print'])->name('student.invoices.print');

        // Refunds
        Route::get('/refunds', [\App\Http\Controllers\Student\RefundController::class, 'index'])->name('student.refunds.index');
        Route::get('/refunds/request/{payment}', [\App\Http\Controllers\Student\RefundController::class, 'create'])->name('student.refunds.create');
        Route::post('/refunds/request/{payment}', [\App\Http\Controllers\Student\RefundController::class, 'store'])->name('student.refunds.store');

        // Book Cart & Checkout
        Route::get('/cart', [CartController::class, 'index'])->name('student.cart.index');
        Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('student.cart.add');
        Route::delete('/cart/remove/{book}', [CartController::class, 'remove'])->name('student.cart.remove');
        Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('student.cart.checkout');
        Route::get('/cart/success', [CartController::class, 'success'])->name('student.cart.success');
        Route::get('/cart/cancel', [CartController::class, 'cancel'])->name('student.cart.cancel');

        // My Purchased Books
        Route::get('/books', [CartController::class, 'purchases'])->name('student.books.index');
    });
});

Route::get('/subjects', [PageController::class, 'subjects'])->name('subjects.index');
Route::redirect('/programs', '/subjects', 301);

Route::get('/books', [PageController::class, 'books'])->name('books.index');
Route::get('/books/{book:slug}', [PageController::class, 'bookDetail'])->name('books.show');

Route::get('/admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->middleware('throttle:10,1')->name('admin.login.post');
Route::post('/admin/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    // Subjects CRUD
    Route::middleware('permission:view-subjects')->group(function () {
        Route::get('/subjects', [SubjectController::class, 'index'])->name('admin.subjects.index');
        Route::get('/subjects/list', [SubjectController::class, 'list'])->name('admin.subjects.list');
        Route::get('/subjects/{subject}', [SubjectController::class, 'show'])->name('admin.subjects.show');
    });
    Route::middleware('permission:manage-subjects')->group(function () {
        Route::post('/subjects', [SubjectController::class, 'store'])->name('admin.subjects.store');
        Route::post('/subjects/change-status', [SubjectController::class, 'changeStatus'])->name('admin.subjects.change-status');
        Route::post('/subjects/bulk-delete', [SubjectController::class, 'bulkDelete'])->name('admin.subjects.bulk-delete');
        Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('admin.subjects.update');
        Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('admin.subjects.destroy');
    });

    // Books CRUD
    Route::middleware('permission:view-books')->group(function () {
        Route::get('/books', [BookController::class, 'index'])->name('admin.books.index');
        Route::get('/books/list', [BookController::class, 'list'])->name('admin.books.list');
        Route::get('/books/{book}', [BookController::class, 'show'])->name('admin.books.show');
    });
    Route::middleware('permission:manage-books')->group(function () {
        Route::post('/books', [BookController::class, 'store'])->name('admin.books.store');
        Route::post('/books/change-status', [BookController::class, 'changeStatus'])->name('admin.books.change-status');
        Route::post('/books/bulk-delete', [BookController::class, 'bulkDelete'])->name('admin.books.bulk-delete');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('admin.books.update');
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('admin.books.destroy');
    });

    // Book Purchases
    Route::middleware('permission:view-book-purchases')->group(function () {
        Route::get('/book-purchases', [BookPurchaseController::class, 'index'])->name('admin.book-purchases.index');
        Route::get('/book-purchases/list', [BookPurchaseController::class, 'list'])->name('admin.book-purchases.list');
    });

    // Slots CRUD (Booking Management)
    Route::middleware('permission:view-bookings')->group(function () {
        Route::get('/calendar', [SlotController::class, 'index'])->name('admin.slots.index');
        Route::get('/slots/list', [SlotController::class, 'list'])->name('admin.slots.list');
        Route::get('/slots/{slot}', [SlotController::class, 'show'])->name('admin.slots.show');
    });
    Route::middleware('permission:manage-bookings')->group(function () {
        Route::post('/slots', [SlotController::class, 'store'])->name('admin.slots.store');
        Route::post('/slots/change-status', [SlotController::class, 'changeStatus'])->name('admin.slots.change-status');
        Route::post('/slots/bulk-delete', [SlotController::class, 'bulkDelete'])->name('admin.slots.bulk-delete');
        Route::put('/slots/{slot}', [SlotController::class, 'update'])->name('admin.slots.update');
        Route::delete('/slots/{slot}', [SlotController::class, 'destroy'])->name('admin.slots.destroy');
    });

    // Students Management
    Route::middleware('permission:view-students')->group(function () {
        Route::get('/students', [StudentController::class, 'index'])->name('admin.students.index');
        Route::get('/students/list', [StudentController::class, 'list'])->name('admin.students.list');
        Route::get('/students/{id}', [StudentController::class, 'show'])->name('admin.students.show');
    });
    Route::middleware('permission:manage-students')->group(function () {
        Route::post('/students/{id}/toggle-status', [StudentController::class, 'toggleStatus'])->name('admin.students.toggle-status');
        Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('admin.students.destroy');
    });

    // Doubts Management
    Route::middleware('permission:view-doubts')->group(function () {
        Route::get('/doubts', [DoubtController::class, 'index'])->name('admin.doubts.index');
        Route::get('/doubts/list', [DoubtController::class, 'list'])->name('admin.doubts.list');
        Route::get('/doubts/{id}', [DoubtController::class, 'show'])->name('admin.doubts.show');
    });
    Route::middleware('permission:manage-doubts')->group(function () {
        Route::post('/doubts/{id}/update-status', [DoubtController::class, 'updateStatus'])->name('admin.doubts.update-status');
        Route::delete('/doubts/{id}', [DoubtController::class, 'destroy'])->name('admin.doubts.destroy');
    });

    // Notifications Management
    Route::middleware('permission:view-notifications')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
        Route::get('/notifications/list', [NotificationController::class, 'list'])->name('admin.notifications.list');
        Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('admin.notifications.show');
        Route::get('/notification-center', [App\Http\Controllers\Admin\NotificationCenterController::class, 'index'])->name('admin.notification-center.index');
        Route::get('/notification-center/list', [App\Http\Controllers\Admin\NotificationCenterController::class, 'list'])->name('admin.notification-center.list');
    });
    Route::middleware('permission:manage-notifications')->group(function () {
        Route::post('/notifications/{id}/resend', [NotificationController::class, 'resend'])->name('admin.notifications.resend');
        
        Route::post('/notification-center/mark-all-read', [App\Http\Controllers\Admin\NotificationCenterController::class, 'markAllRead'])->name('admin.notification-center.mark-all-read');
        Route::post('/notification-center/{id}/read', [App\Http\Controllers\Admin\NotificationCenterController::class, 'markRead'])->name('admin.notification-center.mark-read');
        Route::post('/notification-center/{id}/unread', [App\Http\Controllers\Admin\NotificationCenterController::class, 'markUnread'])->name('admin.notification-center.mark-unread');
        Route::post('/notification-center/{id}/archive', [App\Http\Controllers\Admin\NotificationCenterController::class, 'archive'])->name('admin.notification-center.archive');
        Route::delete('/notification-center/{id}', [App\Http\Controllers\Admin\NotificationCenterController::class, 'destroy'])->name('admin.notification-center.destroy');
    });

    // Appointments Management
    Route::middleware('permission:view-bookings')->group(function () {
        Route::get('/appointments', [AppointmentController::class, 'index'])->name('admin.appointments.index');
        Route::get('/appointments/list', [AppointmentController::class, 'list'])->name('admin.appointments.list');
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('admin.appointments.create');
        Route::get('/appointments/{id}', [AppointmentController::class, 'show'])->name('admin.appointments.show');
    });
    Route::middleware('permission:manage-bookings')->group(function () {
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('admin.appointments.store');
        Route::post('/appointments/{id}/status', [AppointmentController::class, 'updateStatus'])->name('admin.appointments.update-status');
        Route::post('/appointments/{id}/regenerate-meet', [AppointmentController::class, 'regenerateMeet'])->name('admin.appointments.regenerate-meet');
        Route::post('/appointments/{id}/sync-calendar', [AppointmentController::class, 'syncCalendar'])->name('admin.appointments.sync-calendar');
        Route::post('/appointments/{id}/send-email', [AppointmentController::class, 'sendEmail'])->name('admin.appointments.send-email');
        Route::post('/appointments/{id}/generate-invoice', [AppointmentController::class, 'generateInvoice'])->name('admin.appointments.generate-invoice');
        Route::get('/appointments/{id}/print', [AppointmentController::class, 'printBooking'])->name('admin.appointments.print');
        Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->name('admin.appointments.destroy');
    });

    // Admin Booking AJAX APIs
    Route::get('/api/students/search', [AppointmentController::class, 'apiSearchStudents'])->name('admin.api.students.search');
    Route::get('/api/subjects/active', [AppointmentController::class, 'apiActiveSubjects'])->name('admin.api.subjects.active');
    Route::get('/api/students/{userId}/doubts', [AppointmentController::class, 'apiStudentDoubts'])->name('admin.api.students.doubts');
    Route::get('/api/slots/available', [AppointmentController::class, 'apiAvailableSlots'])->name('admin.api.slots.available');

    // Payments
    Route::middleware('permission:view-payments')->group(function () {
        Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
        Route::get('/payments/list', [PaymentController::class, 'list'])->name('admin.payments.list');
        Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('admin.payments.show');
    });
    Route::middleware('permission:manage-payments')->group(function () {
        Route::post('/payments/{id}/status', [PaymentController::class, 'updateStatus'])->name('admin.payments.update-status');
        Route::post('/payments/{id}/refund', [PaymentController::class, 'markRefunded'])->name('admin.payments.refund');
        Route::delete('/payments/{id}', [PaymentController::class, 'destroy'])->name('admin.payments.destroy');
    });

    // Refunds
    Route::middleware('permission:view-refunds')->group(function () {
        Route::get('/refunds', [RefundController::class, 'index'])->name('admin.refunds.index');
        Route::get('/refunds/list', [RefundController::class, 'list'])->name('admin.refunds.list');
    });
    Route::middleware('permission:manage-refunds')->group(function () {
        Route::post('/refunds/{id}/status', [RefundController::class, 'updateStatus'])->name('admin.refunds.update-status');
    });

    // Invoices
    Route::middleware('permission:view-invoices')->group(function () {
        Route::get('/invoices', [InvoiceController::class, 'index'])->name('admin.invoices.index');
        Route::get('/invoices/list', [InvoiceController::class, 'list'])->name('admin.invoices.list');
        Route::get('/invoices/{id}/download', [InvoiceController::class, 'download'])->name('admin.invoices.download');
    });
    Route::middleware('permission:manage-invoices')->group(function () {
        Route::post('/invoices/{id}/regenerate', [InvoiceController::class, 'regenerate'])->name('admin.invoices.regenerate');
        Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('admin.invoices.destroy');
    });

    // Reports
    Route::middleware('permission:view-reports')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('admin.reports.export');
    });

    // Settings
    Route::middleware('permission:manage-settings')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
        Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('admin.settings.update-profile');
        Route::post('/settings/google-connect', [SettingsController::class, 'redirectGoogle'])->name('admin.settings.google-connect');
        Route::get('/settings/google-callback', [SettingsController::class, 'callbackGoogle'])->name('admin.settings.google-callback');
        Route::post('/settings/google-disconnect', [SettingsController::class, 'disconnectGoogle'])->name('admin.settings.google-disconnect');
        Route::post('/settings/google-test', [SettingsController::class, 'testGoogle'])->name('admin.settings.google-test');
        Route::post('/settings/verify-calendar', [SettingsController::class, 'verifyCalendar'])->name('admin.settings.verify-calendar');
        Route::post('/settings/test-meet', [SettingsController::class, 'testMeet'])->name('admin.settings.test-meet');
        Route::post('/settings/switch-calendar', [SettingsController::class, 'switchCalendar'])->name('admin.settings.switch-calendar');
        Route::post('/settings/smtp-verify', [SettingsController::class, 'verifySmtp'])->name('admin.settings.smtp-verify');
        Route::post('/settings/smtp-test', [SettingsController::class, 'sendTestEmail'])->name('admin.settings.smtp-test');
        Route::get('/website-settings', [\App\Http\Controllers\Admin\WebsiteSettingsController::class, 'index'])->name('admin.website-settings.index');
        Route::post('/website-settings', [\App\Http\Controllers\Admin\WebsiteSettingsController::class, 'update'])->name('admin.website-settings.update');
    });

    // Audit Logs
    Route::middleware('permission:view-audit-logs')->group(function () {
        Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('admin.audit-logs.index');
        Route::get('/audit-logs/list', [\App\Http\Controllers\Admin\AuditLogController::class, 'list'])->name('admin.audit-logs.list');
        Route::get('/audit-logs/{id}', [\App\Http\Controllers\Admin\AuditLogController::class, 'show'])->name('admin.audit-logs.show');
    });

    // Role Management
    Route::middleware('permission:manage-roles')->group(function () {
        Route::get('/roles', [\App\Http\Controllers\Admin\RoleController::class, 'index'])->name('admin.roles.index');
        Route::get('/roles/list', [\App\Http\Controllers\Admin\RoleController::class, 'list'])->name('admin.roles.list');
        Route::post('/roles', [\App\Http\Controllers\Admin\RoleController::class, 'store'])->name('admin.roles.store');
        Route::get('/roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'show'])->name('admin.roles.show');
        Route::put('/roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'update'])->name('admin.roles.update');
        Route::delete('/roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('admin.roles.destroy');
    });

    // Staff Management
    Route::middleware('permission:manage-staff')->group(function () {
        Route::get('/staff', [\App\Http\Controllers\Admin\StaffController::class, 'index'])->name('admin.staff.index');
        Route::get('/staff/list', [\App\Http\Controllers\Admin\StaffController::class, 'list'])->name('admin.staff.list');
        Route::post('/staff', [\App\Http\Controllers\Admin\StaffController::class, 'store'])->name('admin.staff.store');
        Route::get('/staff/{user}', [\App\Http\Controllers\Admin\StaffController::class, 'show'])->name('admin.staff.show');
        Route::put('/staff/{user}', [\App\Http\Controllers\Admin\StaffController::class, 'update'])->name('admin.staff.update');
        Route::delete('/staff/{user}', [\App\Http\Controllers\Admin\StaffController::class, 'destroy'])->name('admin.staff.destroy');
    });

    Route::get('/subjects/create', function () {
        return view('admin.subjects.create');
    })->name('admin.subjects.create');
});
