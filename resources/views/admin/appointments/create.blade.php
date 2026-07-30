@extends('layouts.admin')

@section('title', 'Create Booking')
@section('page_title', 'Appointments')

@section('content')
<div class="space-y-6">
    <a href="{{ route('admin.appointments.index') }}" class="text-sm font-semibold text-primary flex items-center gap-2 hover:underline">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back to Appointments
    </a>

    <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-border">
            <h3 class="text-lg font-bold text-foreground">Create New Booking</h3>
            <p class="text-sm text-muted-foreground mt-1">Book a session on behalf of a student.</p>
        </div>

        <form class="p-6 space-y-6" id="booking-form" method="POST" action="{{ route('admin.appointments.store') }}">
            @csrf

            @if ($errors->any())
                <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-lg">
                    <ul class="list-disc list-inside text-sm text-rose-600 font-semibold space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Student Selection --}}
            <div class="space-y-1 relative" id="student-search-wrapper">
                <label class="text-sm font-semibold text-foreground">Student <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground"></i>
                    <input type="text" id="student-search" placeholder="Search by name or email..."
                        class="w-full pl-10 pr-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all"
                        autocomplete="off">
                    <input type="hidden" name="student_id" id="student_id" value="{{ old('student_id') }}">
                </div>
                <div id="student-dropdown" class="absolute z-50 w-full mt-1 bg-card border border-border rounded-lg shadow-lg hidden max-h-60 overflow-y-auto"></div>
                <div id="selected-student" class="hidden mt-2 p-3 bg-muted/30 border border-border rounded-lg flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary text-sm font-bold border border-primary/20" id="student-avatar"></div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-foreground text-sm" id="student-name"></p>
                        <p class="text-xs text-muted-foreground" id="student-email"></p>
                    </div>
                    <button type="button" onclick="clearStudent()" class="text-muted-foreground hover:text-rose-500 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            {{-- Subject --}}
            <div class="space-y-1">
                <label class="text-sm font-semibold text-foreground">Subject <span class="text-rose-500">*</span></label>
                <select name="subject_id" id="subject_id" required
                    class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                    <option value="">Select a subject</option>
                </select>
            </div>

            {{-- Doubt Selection --}}
            <div class="space-y-2">
                <label class="text-sm font-semibold text-foreground">Doubt <span class="text-rose-500">*</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="doubt_type" value="existing" checked class="accent-primary" onchange="toggleDoubtType()">
                        <span class="text-sm font-medium text-foreground">Existing Doubt</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="doubt_type" value="new" class="accent-primary" onchange="toggleDoubtType()">
                        <span class="text-sm font-medium text-foreground">Create New</span>
                    </label>
                </div>

                <div id="existing-doubt-section">
                    <select name="doubt_id" id="doubt_id" class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                        <option value="">Select a student first</option>
                    </select>
                </div>

                <div id="new-doubt-section" class="hidden space-y-3">
                    <input type="text" name="doubt_topic" id="doubt_topic" placeholder="Topic (e.g. Algebra, Photosynthesis)"
                        class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                    <input type="text" name="doubt_title" id="doubt_title" placeholder="Doubt title"
                        class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                    <textarea name="doubt_description" id="doubt_description" rows="3" placeholder="Describe the doubt..."
                        class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all"></textarea>
                </div>
            </div>

            {{-- Date & Slot --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-foreground">Appointment Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="appointment_date" id="appointment_date" required min="{{ now()->toDateString() }}"
                        class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all"
                        onchange="loadSlots()">
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-foreground">Time Slot <span class="text-rose-500">*</span></label>
                    <select name="slot_id" id="slot_id" required
                        class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                        <option value="">Select a date first</option>
                    </select>
                </div>
            </div>

            {{-- Settings --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-foreground">Duration (minutes) <span class="text-rose-500">*</span></label>
                    <input type="number" name="duration" id="duration" value="{{ \App\Models\Setting::get('session_duration', 50) }}" min="10" required
                        class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-foreground">Meeting Type</label>
                    <div class="px-4 py-2 bg-muted/30 border border-border rounded-lg flex items-center gap-2">
                        <i data-lucide="video" class="w-4 h-4 text-primary"></i>
                        <span class="text-sm font-semibold text-foreground">Google Meet</span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 text-[10px] font-bold uppercase tracking-wider">Auto-generated</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-foreground">Payment Status <span class="text-rose-500">*</span></label>
                    <select name="payment_status" id="payment_status" required onchange="toggleAmountField()"
                        class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="manual">Manual Payment</option>
                        <option value="waived">Waived (Free)</option>
                    </select>
                </div>
                <div class="space-y-1" id="amount-field">
                    <label class="text-sm font-semibold text-foreground">Amount ($)</label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ \App\Models\Setting::get('session_price', '32.00') }}"
                        class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-foreground">Booking Status <span class="text-rose-500">*</span></label>
                    <select name="booking_status" id="booking_status" required
                        class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                        <option value="pending">Pending</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="confirmed">Confirmed</option>
                    </select>
                </div>
            </div>

            {{-- Admin Notes --}}
            <div class="space-y-1">
                <label class="text-sm font-semibold text-foreground">Admin Notes</label>
                <textarea name="admin_notes" rows="3" placeholder="Internal notes about this booking..."
                    class="w-full px-4 py-2 bg-muted/50 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-ring transition-all"></textarea>
            </div>

            <div class="pt-4 flex items-center gap-3">
                <button type="submit" id="submit-btn" class="px-6 py-2 bg-primary text-primary-foreground rounded-lg text-sm font-bold hover:opacity-90 shadow-sm transition-all flex items-center gap-2">
                    <i data-lucide="calendar-plus" class="w-4 h-4"></i> Create Booking
                </button>
                <a href="{{ route('admin.appointments.index') }}" class="px-6 py-2 bg-background border border-border text-muted-foreground rounded-lg text-sm font-bold hover:bg-accent hover:text-accent-foreground transition-all inline-block text-center">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let searchTimeout = null;
    let selectedStudent = null;

    document.addEventListener('DOMContentLoaded', () => {
        loadActiveSubjects();
    });

    document.getElementById('student-search').addEventListener('input', function (e) {
        clearTimeout(searchTimeout);
        const q = e.target.value.trim();
        if (q.length < 2) {
            document.getElementById('student-dropdown').classList.add('hidden');
            return;
        }
        searchTimeout = setTimeout(() => searchStudents(q), 300);
    });

    document.addEventListener('click', function (e) {
        if (!document.getElementById('student-search-wrapper').contains(e.target)) {
            document.getElementById('student-dropdown').classList.add('hidden');
        }
    });

    async function searchStudents(q) {
        try {
            const res = await fetch(`/admin/api/students/search?q=${encodeURIComponent(q)}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const result = await res.json();
            const dropdown = document.getElementById('student-dropdown');
            if (!result.data.length) {
                dropdown.innerHTML = '<div class="px-4 py-3 text-sm text-muted-foreground">No students found</div>';
                dropdown.classList.remove('hidden');
                return;
            }
            dropdown.innerHTML = result.data.map(s => `
                <div class="px-4 py-3 hover:bg-accent cursor-pointer flex items-center gap-3 border-b border-border/50 last:border-0"
                     onclick="selectStudent(${s.id}, '${escapeHtml(s.name)}', '${escapeHtml(s.email)}', '${s.student_class || ''}')">
                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-[10px] font-bold border border-primary/20">
                        ${s.name.charAt(0)}
                    </div>
                    <div>
                        <p class="font-bold text-foreground text-xs">${escapeHtml(s.name)}</p>
                        <p class="text-[10px] text-muted-foreground">${escapeHtml(s.email)}${s.student_class ? ' · Year ' + s.student_class : ''}</p>
                    </div>
                </div>
            `).join('');
            dropdown.classList.remove('hidden');
        } catch (err) {
            console.error(err);
        }
    }

    function selectStudent(id, name, email, studentClass) {
        selectedStudent = { id, name, email, studentClass };
        document.getElementById('student_id').value = id;
        document.getElementById('student-search').value = '';
        document.getElementById('student-dropdown').classList.add('hidden');
        document.getElementById('student-avatar').textContent = name.charAt(0);
        document.getElementById('student-name').textContent = name;
        document.getElementById('student-email').textContent = email + (studentClass ? ' · Class ' + studentClass : '');
        document.getElementById('selected-student').classList.remove('hidden');
        document.getElementById('student-search').parentElement.classList.add('hidden');
        loadStudentDoubts(id);
    }

    function clearStudent() {
        selectedStudent = null;
        document.getElementById('student_id').value = '';
        document.getElementById('selected-student').classList.add('hidden');
        document.getElementById('student-search').parentElement.classList.remove('hidden');
        document.getElementById('doubt_id').innerHTML = '<option value="">Select a student first</option>';
    }

    async function loadActiveSubjects() {
        try {
            const res = await fetch('/admin/api/subjects/active', {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const result = await res.json();
            const select = document.getElementById('subject_id');
            result.data.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.name;
                opt.dataset.duration = s.session_duration || {{ \App\Models\Setting::get('session_duration', 50) }};
                select.appendChild(opt);
            });
        } catch (err) {
            console.error(err);
        }
    }

    document.getElementById('subject_id').addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (opt.dataset.duration) {
            document.getElementById('duration').value = opt.dataset.duration;
        }
        if (selectedStudent) {
            loadStudentDoubts(selectedStudent.id, this.value);
        }
    });

    async function loadStudentDoubts(userId, subjectId = null) {
        try {
            let url = `/admin/api/students/${userId}/doubts`;
            if (subjectId) url += `?subject_id=${subjectId}`;
            const res = await fetch(url, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const result = await res.json();
            const select = document.getElementById('doubt_id');
            select.innerHTML = '<option value="">Select a doubt</option>';
            result.data.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id;
                opt.textContent = `${d.title} (${d.subject?.name || ''})`;
                select.appendChild(opt);
            });
            if (!result.data.length) {
                select.innerHTML = '<option value="">No available doubts for this student</option>';
            }
        } catch (err) {
            console.error(err);
        }
    }

    function toggleDoubtType() {
        const type = document.querySelector('input[name="doubt_type"]:checked').value;
        document.getElementById('existing-doubt-section').classList.toggle('hidden', type !== 'existing');
        document.getElementById('new-doubt-section').classList.toggle('hidden', type !== 'new');
    }

    async function loadSlots() {
        const date = document.getElementById('appointment_date').value;
        if (!date) return;
        try {
            const res = await fetch(`/admin/api/slots/available?date=${date}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const result = await res.json();
            const select = document.getElementById('slot_id');
            select.innerHTML = '<option value="">Select a time slot</option>';
            result.data.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = `${formatTime(s.start_time)} - ${formatTime(s.end_time)}`;
                select.appendChild(opt);
            });
            if (!result.data.length) {
                select.innerHTML = '<option value="">No available slots for this date</option>';
            }
        } catch (err) {
            console.error(err);
        }
    }

    function toggleAmountField() {
        const status = document.getElementById('payment_status').value;
        const field = document.getElementById('amount-field');
        const input = document.getElementById('amount');
        if (status === 'waived') {
            input.value = '0';
            input.disabled = true;
        } else {
            input.disabled = false;
            if (!input.value || input.value === '0') {
                input.value = '{{ \App\Models\Setting::get("session_price", "32.00") }}';
            }
        }
    }

    function formatTime(time) {
        const [h, m] = time.substring(0, 5).split(':');
        const hour = parseInt(h);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const h12 = hour % 12 || 12;
        return `${h12}:${m} ${ampm}`;
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
</script>
@endpush
