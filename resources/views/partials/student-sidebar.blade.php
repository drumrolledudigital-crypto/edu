<div class="bg-white rounded-[2rem] shadow-sm border border-gray-50 p-6 sticky top-28">
    <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-100">
        <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xl font-bold">
            {{ substr(auth()->user()->name, 0, 1) }}
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="font-extrabold text-secondary truncate">{{ auth()->user()->name }}</h3>
            <p class="text-xs text-gray-500">{{ auth()->user()->student_class }}</p>
        </div>
    </div>

    <nav class="space-y-1 font-bold text-[13px] text-gray-500">
        <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('student.dashboard') ? 'bg-primary/5 text-primary' : 'hover:bg-light hover:text-secondary' }} transition-colors">
            <i class="fas fa-home w-5"></i> Dashboard
        </a>
        <a href="{{ route('student.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('student.profile') ? 'bg-primary/5 text-primary' : 'hover:bg-light hover:text-secondary' }} transition-colors">
            <i class="fas fa-user-edit w-5"></i> My Profile
        </a>
        
        <div class="h-px bg-gray-50 my-4 mx-4"></div>

        <a href="{{ route('student.booking.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('student.booking.create') ? 'bg-primary/5 text-primary' : 'hover:bg-light hover:text-secondary' }} transition-colors">
            <i class="fas fa-calendar-plus w-5"></i> Book Session
        </a>
        <a href="{{ route('doubts.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('doubts.create') ? 'bg-primary/5 text-primary' : 'hover:bg-light hover:text-secondary' }} transition-colors">
            <i class="fas fa-question-circle w-5"></i> Submit Doubt
        </a>
        
        <div class="h-px bg-gray-50 my-4 mx-4"></div>
        
        <a href="{{ route('student.doubts.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('student.doubts.index*') ? 'bg-primary/5 text-primary' : 'hover:bg-light hover:text-secondary' }} transition-colors">
            <i class="fas fa-history w-5"></i> My Doubts
        </a>
        <a href="{{ route('student.booking.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('student.booking.index*') ? 'bg-primary/5 text-primary' : 'hover:bg-light hover:text-secondary' }} transition-colors">
            <i class="fas fa-calendar-check w-5"></i> My Bookings
        </a>
        
        <div class="h-px bg-gray-50 my-4 mx-4"></div>

        <a href="{{ route('student.sessions.upcoming') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('student.sessions.upcoming') ? 'bg-primary/5 text-primary' : 'hover:bg-light hover:text-secondary' }} transition-colors">
            <i class="fas fa-video w-5"></i> Upcoming Sessions
        </a>
        <a href="{{ route('student.sessions.past') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('student.sessions.past') ? 'bg-primary/5 text-primary' : 'hover:bg-light hover:text-secondary' }} transition-colors">
            <i class="fas fa-check-circle w-5"></i> Past Sessions
        </a>
        <a href="{{ route('student.payments.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('student.payments.history') ? 'bg-primary/5 text-primary' : 'hover:bg-light hover:text-secondary' }} transition-colors">
            <i class="fas fa-credit-card w-5"></i> Payment History
        </a>
        <a href="{{ route('student.invoices.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('student.invoices.*') ? 'bg-primary/5 text-primary' : 'hover:bg-light hover:text-secondary' }} transition-colors">
            <i class="fas fa-file-invoice w-5"></i> Invoices
        </a>

        <div class="h-px bg-gray-50 my-4 mx-4"></div>

        <form method="POST" action="{{ route('student.logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-red-50 text-red-500 transition-colors">
                <i class="fas fa-sign-out-alt w-5"></i> Logout
            </button>
        </form>
    </nav>
</div>
