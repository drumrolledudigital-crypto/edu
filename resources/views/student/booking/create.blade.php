@extends('layouts.student-app')

@section('title', 'Book a Session | ' . \App\Models\Setting::get('platform_name', 'Drumroll Edu'))

@section('content')

<!-- Mobile Booking -->
<div class="lg:hidden">
    <x-mobile.page-header title="Book a Session" subtitle="Pick a subject then choose your date & time" icon="fas fa-calendar-plus" />

    @if($errors->any())
    <div class="px-4 py-2">
        <div class="p-3 rounded-xl text-sm font-bold bg-rose-50 text-rose-600 border border-rose-100 flex items-center gap-2 fade-in-up" data-animate>
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    </div>
    @endif

    <form action="{{ route('student.booking.store') }}" method="POST" id="booking-form-mobile">
        @csrf
        <input type="hidden" name="subject_id" id="selected-subject-id-mobile">
        <input type="hidden" name="slot_id" id="selected-slot-id-mobile">

        <!-- Step 1: Subject Selection -->
        <div class="px-4 pb-3">
            <div class="bg-white rounded-2xl p-4 shadow-card border border-gray-50 fade-in-up" data-animate>
                <h3 class="font-extrabold text-secondary mb-3 flex items-center gap-2 text-sm">
                    <i class="fas fa-book-open text-primary"></i> 1. Select Subject
                </h3>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($subjects as $subject)
                    <button type="button"
                        class="subject-btn-mobile flex flex-col items-center gap-1 p-3 rounded-xl border-2 border-gray-100 bg-light transition-all btn-haptic text-center"
                        data-subject-id="{{ $subject->id }}"
                        onclick="selectSubjectMobile({{ $subject->id }}, this, '{{ $subject->name }}')">
                        <i class="fas fa-{{ $subject->icon ?: 'book' }} text-lg"></i>
                        <span class="text-xs font-bold text-secondary">{{ $subject->name }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Step 2: Calendar -->
        <div class="px-4 pb-3">
            <div class="bg-white rounded-2xl p-4 shadow-card border border-gray-50 fade-in-up opacity-50 pointer-events-none transition-all" id="calendar-section-mobile" data-animate style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-extrabold text-secondary flex items-center gap-2 text-sm">
                        <i class="fas fa-calendar-alt text-primary"></i> 2. Choose Date
                    </h3>
                    <div class="flex items-center gap-1">
                        <button type="button" onclick="navigateWeekMobile(-1)" class="w-8 h-8 rounded-lg bg-light flex items-center justify-center text-gray-400 hover:text-primary transition-colors"><i class="fas fa-chevron-left text-xs"></i></button>
                        <span class="text-xs font-bold text-secondary w-28 text-center" id="week-label-mobile"></span>
                        <button type="button" onclick="navigateWeekMobile(1)" class="w-8 h-8 rounded-lg bg-light flex items-center justify-center text-gray-400 hover:text-primary transition-colors"><i class="fas fa-chevron-right text-xs"></i></button>
                    </div>
                </div>
                <div class="grid grid-cols-7 gap-1 mb-2">
                    @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                    <div class="text-[10px] font-bold text-gray-400 text-center uppercase py-1">{{ $day }}</div>
                    @endforeach
                </div>
                <div id="calendar-grid-mobile" class="grid grid-cols-7 gap-1">
                </div>
            </div>
        </div>

        <!-- Step 3: Time Slots -->
        <div class="px-4 pb-3">
            <div class="bg-white rounded-2xl p-4 shadow-card border border-gray-50 fade-in-up opacity-50 pointer-events-none transition-all" id="slots-section-mobile" data-animate style="animation-delay: 0.15s;">
                <h3 class="font-extrabold text-secondary mb-3 flex items-center gap-2 text-sm">
                    <i class="fas fa-clock text-primary"></i> 3. Select Time
                </h3>
                <div id="slots-container-mobile" class="space-y-2">
                    <div class="text-center py-6 text-gray-400 text-sm">
                        <i class="fas fa-hand-pointer mb-2 block text-lg opacity-30"></i>
                        Select a date first
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary & Submit -->
        <div class="px-4 pb-32">
            <div class="bg-secondary rounded-2xl p-5 text-white shadow-elevated fade-in-up opacity-50 pointer-events-none transition-all" id="summary-mobile" data-animate style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-[10px] font-bold text-white/50 uppercase tracking-wider">Subject</p>
                        <p class="text-sm font-bold" id="summary-subject-mobile">--</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-white/50 uppercase tracking-wider">Date</p>
                        <p class="text-sm font-bold" id="summary-date-mobile">--</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-white/50 uppercase tracking-wider">Time</p>
                        <p class="text-sm font-bold" id="summary-time-mobile">--</p>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-[10px] font-bold text-white/50 uppercase tracking-wider">Duration</p>
                        <p class="text-lg font-black text-accent">{{ \App\Models\Setting::get('session_duration', 50) }} min</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-white/50 uppercase tracking-wider">Price</p>
                        <p class="text-lg font-black text-accent">${{ number_format(\App\Models\Setting::get('session_price', 32), 2) }}</p>
                    </div>
                </div>
                <button type="submit" class="w-full bg-primary hover:bg-white hover:text-primary text-white font-bold py-3.5 rounded-xl btn-haptic shadow-lg transition-all">
                    Proceed to Payment
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Desktop Booking -->
<div class="hidden lg:block bg-light min-h-screen py-12 px-4 md:px-12">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

        <aside class="w-full lg:w-64 shrink-0">@include('partials.student-sidebar')</aside>

        <main class="flex-1 space-y-8">

            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-black text-secondary uppercase tracking-tight">Book a <span class="text-primary">Session</span></h1>
                <p class="text-gray-500 mt-1">Select a subject, pick a date from the calendar, and choose your preferred time slot.</p>
            </div>

            @if($errors->any())
                <div class="p-4 rounded-xl bg-red-50 text-red-600 border border-red-200 font-bold mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('student.booking.store') }}" method="POST" id="booking-form" class="space-y-8">
                @csrf
                <input type="hidden" name="subject_id" id="selected-subject-id">
                <input type="hidden" name="slot_id" id="selected-slot-id">

                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">

                    <!-- Left: Subject Selection + Calendar -->
                    <div class="lg:col-span-3 space-y-8">

                        <!-- Step 1: Subject -->
                        <div class="bg-white rounded-[2rem] shadow-soft border border-gray-50 p-8 fade-up">
                            <h3 class="text-xl font-extrabold text-secondary mb-6 flex items-center gap-2">
                                <i class="fas fa-book-open text-primary"></i> 1. Select Subject
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($subjects as $subject)
                                <button type="button"
                                    class="subject-btn flex flex-col items-center gap-2 p-5 rounded-2xl border-2 border-gray-50 bg-light text-center transition-all hover:border-primary/30 group"
                                    data-subject-id="{{ $subject->id }}"
                                    onclick="selectSubject({{ $subject->id }}, this, '{{ $subject->name }}')">
                                    <i class="fas fa-{{ $subject->icon ?: 'book' }} text-3xl"></i>
                                    <span class="text-sm font-extrabold text-secondary group-hover:text-primary transition-colors">{{ $subject->name }}</span>
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Step 2: Calendar -->
                        <div class="bg-white rounded-[2rem] shadow-soft border border-gray-50 p-8 fade-up opacity-50 pointer-events-none transition-all duration-500" id="calendar-section">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-extrabold text-secondary flex items-center gap-2">
                                    <i class="fas fa-calendar-alt text-primary"></i> 2. Choose Date
                                </h3>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="navigateWeek(-1)" class="w-10 h-10 rounded-xl bg-light flex items-center justify-center text-gray-400 hover:text-primary hover:bg-primary/5 transition-all"><i class="fas fa-chevron-left"></i></button>
                                    <span class="text-sm font-extrabold text-secondary w-36 text-center" id="week-label"></span>
                                    <button type="button" onclick="navigateWeek(1)" class="w-10 h-10 rounded-xl bg-light flex items-center justify-center text-gray-400 hover:text-primary hover:bg-primary/5 transition-all"><i class="fas fa-chevron-right"></i></button>
                                </div>
                            </div>
                            <div class="grid grid-cols-7 gap-2 mb-3">
                                @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                                <div class="text-[11px] font-black text-gray-400 text-center uppercase tracking-wider py-1">{{ $day }}</div>
                                @endforeach
                            </div>
                            <div id="calendar-grid" class="grid grid-cols-7 gap-2">
                            </div>
                        </div>

                        <!-- Step 3: Time Slots -->
                        <div class="bg-white rounded-[2rem] shadow-soft border border-gray-50 p-8 fade-up opacity-50 pointer-events-none transition-all duration-500" id="slots-section">
                            <h3 class="text-xl font-extrabold text-secondary mb-6 flex items-center gap-2">
                                <i class="fas fa-clock text-primary"></i> 3. Select Time
                            </h3>
                            <div id="slots-container" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <div class="col-span-full text-center py-10 text-gray-400 text-sm font-medium">
                                    <i class="fas fa-calendar-day mb-2 block text-2xl opacity-20"></i>
                                    Select a date to see available slots
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right: Summary -->
                    <div class="lg:col-span-2 lg:sticky lg:top-28">
                        <div class="bg-secondary rounded-[2.5rem] p-8 md:p-10 text-white relative overflow-hidden shadow-2xl opacity-50 pointer-events-none transition-all duration-500" id="summary-container">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                            <div class="relative z-10">
                                <h2 class="text-2xl font-black mb-8 uppercase tracking-wider text-primary">Booking Summary</h2>
                                <div class="space-y-5">
                                    <div>
                                        <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Subject</p>
                                        <p class="font-bold text-lg" id="summary-subject">Not selected</p>
                                    </div>
                                    <div class="border-t border-white/5 pt-5">
                                        <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Date</p>
                                        <p class="font-bold text-lg" id="summary-date">Not selected</p>
                                    </div>
                                    <div class="border-t border-white/5 pt-5">
                                        <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Time</p>
                                        <p class="font-bold text-lg" id="summary-time">Not selected</p>
                                    </div>
                                    <div class="border-t border-white/5 pt-5">
                                        <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Duration</p>
                                        <p class="text-xl font-black text-accent">{{ \App\Models\Setting::get('session_duration', 50) }} Minutes</p>
                                    </div>
                                    <div class="border-t border-white/5 pt-5">
                                        <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Price</p>
                                        <p class="text-3xl font-black text-accent">${{ number_format(\App\Models\Setting::get('session_price', 32), 2) }}</p>
                                    </div>
                                </div>
                                <button type="submit" class="w-full bg-primary hover:bg-white hover:text-primary text-white font-black py-5 px-12 rounded-full shadow-lg transition-all duration-300 transform hover:-translate-y-1 text-lg mt-8">
                                    Proceed to Payment
                                </button>
                                <p class="text-[10px] text-white/40 mt-4 font-bold uppercase tracking-widest italic text-center">One student per slot</p>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </main>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ============================================================
    // Data — pre-loaded weeks from server
    // ============================================================
    const weeksData = @json($weeks);
    let currentWeekIndex = 0;
    let selectedDate = null;
    let selectedSlotId = null;
    let selectedSubjectId = null;
    let selectedSubjectName = '';

    // ============================================================
    // Subject Selection
    // ============================================================
    function selectSubject(id, btn, name) {
        document.querySelectorAll('.subject-btn').forEach(b => {
            b.classList.remove('border-primary', 'bg-white', 'shadow-soft');
            b.classList.add('border-gray-50', 'bg-light');
        });
        btn.classList.add('border-primary', 'bg-white', 'shadow-soft');
        btn.classList.remove('border-gray-50', 'bg-light');

        selectedSubjectId = id;
        selectedSubjectName = name;
        document.getElementById('selected-subject-id').value = id;

        const calSection = document.getElementById('calendar-section');
        calSection.classList.remove('opacity-50', 'pointer-events-none');

        document.getElementById('summary-subject').textContent = name;
    }

    function selectSubjectMobile(id, btn, name) {
        document.querySelectorAll('.subject-btn-mobile').forEach(b => {
            b.classList.remove('border-primary', 'bg-white', 'shadow-card');
            b.classList.add('border-gray-100', 'bg-light');
        });
        btn.classList.add('border-primary', 'bg-white', 'shadow-card');
        btn.classList.remove('border-gray-100', 'bg-light');

        selectedSubjectId = id;
        selectedSubjectName = name;
        document.getElementById('selected-subject-id-mobile').value = id;

        const calSection = document.getElementById('calendar-section-mobile');
        calSection.classList.remove('opacity-50', 'pointer-events-none');

        document.getElementById('summary-subject-mobile').textContent = name;
    }

    // ============================================================
    // Calendar Rendering
    // ============================================================
    function renderCalendar(containerId, weekLabelId, weekIdx, isMobile) {
        const container = document.getElementById(containerId);
        const label = document.getElementById(weekLabelId);
        const week = weeksData[weekIdx];

        if (!week) return;

        const startDate = new Date(week.start + 'T00:00:00');
        const endDate = new Date(week.end + 'T00:00:00');
        const options = { month: 'short', day: 'numeric' };
        label.textContent = startDate.toLocaleDateString('en-US', options) + ' - ' + endDate.toLocaleDateString('en-US', options);

        container.innerHTML = '';
        week.days.forEach(day => {
            const cell = document.createElement('button');
            cell.type = 'button';
            cell.className = 'calendar-date text-center rounded-xl transition-all py-2 ' + getDayClasses(day, isMobile);
            cell.dataset.date = day.date;

            const dayEl = document.createElement('div');
            dayEl.className = 'text-[10px] font-bold ' + (day.isToday ? 'text-primary' : 'text-gray-400') + ' uppercase';
            dayEl.textContent = day.day;

            const numEl = document.createElement('div');
            numEl.className = 'text-sm font-black ' + (day.isToday ? 'text-primary' : 'text-secondary') + ' mt-0.5';
            numEl.textContent = day.dayNum;

            cell.appendChild(dayEl);
            cell.appendChild(numEl);

            if (day.hasSlots && !day.isPast) {
                const dot = document.createElement('div');
                dot.className = 'w-1.5 h-1.5 rounded-full bg-green-400 mx-auto mt-0.5 available-dot';
                cell.appendChild(dot);
                cell.addEventListener('click', function() {
                    if (isMobile) selectDateMobile(day.date, this);
                    else selectDate(day.date, this);
                });
            }

            if (day.isPast || !day.hasSlots) {
                cell.disabled = true;
            }

            if (!isMobile && day.date === selectedDate) {
                cell.classList.add('border-primary', 'bg-primary/5');
            }

            container.appendChild(cell);
        });
    }

    function getDayClasses(day, isMobile) {
        let classes = 'border-2 ';
        if (isMobile) {
            if (day.isPast || !day.hasSlots) {
                classes += 'border-gray-50 bg-gray-50/50 text-gray-300 cursor-not-allowed opacity-50 ';
            } else if (day.isToday) {
                classes += 'border-primary/30 bg-primary/5 cursor-pointer ';
            } else {
                classes += 'border-gray-100 bg-light cursor-pointer hover:border-primary/30 hover:bg-white ';
            }
        } else {
            if (day.isPast || !day.hasSlots) {
                classes += 'border-gray-50 bg-gray-50/50 text-gray-300 cursor-not-allowed opacity-50 ';
            } else if (day.isToday) {
                classes += 'border-primary/30 bg-primary/5 cursor-pointer hover:bg-primary/10 ';
            } else {
                classes += 'border-gray-50 bg-light cursor-pointer hover:border-primary/30 hover:bg-white ';
            }
        }
        return classes;
    }

    function navigateWeek(dir, isMobile) {
        const newIdx = currentWeekIndex + dir;
        if (newIdx < 0 || newIdx >= weeksData.length) return;
        currentWeekIndex = newIdx;
        const suffix = isMobile ? '-mobile' : '';
        renderCalendar('calendar-grid' + suffix, 'week-label' + suffix, currentWeekIndex, !!isMobile);
        if (isMobile) navigateWeekMobile = function(d) { navigateWeek(d, true); };
    }

    function navigateWeekMobile(dir) { navigateWeek(dir, true); }

    // ============================================================
    // Date Selection
    // ============================================================
    function selectDate(date, btn) {
        selectedDate = date;

        document.querySelectorAll('#calendar-grid .calendar-date').forEach(c => {
            c.classList.remove('border-primary', 'bg-primary/5');
            if (!c.disabled) {
                c.classList.add('border-gray-50', 'bg-light');
            }
        });
        btn.classList.add('border-primary', 'bg-primary/5');
        btn.classList.remove('border-gray-50', 'bg-light');

        const slotsSection = document.getElementById('slots-section');
        slotsSection.classList.remove('opacity-50', 'pointer-events-none');

        renderSlots(date, 'slots-container', false);

        const formattedDate = new Date(date + 'T00:00:00').toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
        document.getElementById('summary-date').textContent = formattedDate;

        selectedSlotId = null;
        document.getElementById('selected-slot-id').value = '';
        document.getElementById('summary-time').textContent = 'Not selected';
        document.getElementById('summary-container').classList.add('opacity-50', 'pointer-events-none');
    }

    function selectDateMobile(date, btn) {
        selectedDate = date;

        document.querySelectorAll('#calendar-grid-mobile .calendar-date').forEach(c => {
            c.classList.remove('border-primary', 'bg-primary/5');
            if (!c.disabled) {
                c.classList.add('border-gray-100', 'bg-light');
            }
        });
        btn.classList.add('border-primary', 'bg-primary/5');
        btn.classList.remove('border-gray-100', 'bg-light');

        const slotsSection = document.getElementById('slots-section-mobile');
        slotsSection.classList.remove('opacity-50', 'pointer-events-none');

        renderSlots(date, 'slots-container-mobile', true);

        const formattedDate = new Date(date + 'T00:00:00').toLocaleDateString('en-GB', { day: '2-digit', month: 'short' });
        document.getElementById('summary-date-mobile').textContent = formattedDate;

        selectedSlotId = null;
        document.getElementById('selected-slot-id-mobile').value = '';
        document.getElementById('summary-time-mobile').textContent = '--';
        document.getElementById('summary-mobile').classList.add('opacity-50', 'pointer-events-none');
    }

    // ============================================================
    // Slot Rendering
    // ============================================================
    function renderSlots(date, containerId, isMobile) {
        const container = document.getElementById(containerId);
        let slots = [];

        for (const week of weeksData) {
            for (const day of week.days) {
                if (day.date === date) {
                    slots = day.slots || [];
                    break;
                }
            }
            if (slots.length) break;
        }

        if (!slots.length) {
            container.innerHTML = `
                <div class="col-span-full text-center py-6 text-gray-400 text-sm">
                    <i class="fas fa-clock mb-2 block text-lg opacity-30"></i>
                    No available slots for this date
                </div>
            `;
            return;
        }

        container.innerHTML = '';
        let gridClass = isMobile ? 'space-y-2' : 'grid grid-cols-2 sm:grid-cols-3 gap-3';

        if (!isMobile) container.className = gridClass;

        slots.forEach(slot => {
            const slotBtn = document.createElement('button');
            slotBtn.type = 'button';
            slotBtn.className = isMobile
                ? 'w-full flex items-center justify-between p-3 rounded-xl border-2 border-gray-100 bg-light transition-all btn-haptic text-left slot-btn-mb'
                : 'slot-btn relative flex items-center justify-between p-4 rounded-xl border-2 border-gray-50 bg-light hover:border-primary transition-all group';
            slotBtn.dataset.slotId = slot.id;
            slotBtn.dataset.startTime = slot.start_time;
            slotBtn.dataset.endTime = slot.end_time;

            const timeText = formatTime(slot.start_time) + ' - ' + formatTime(slot.end_time);

            if (isMobile) {
                slotBtn.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-gray-400 shadow-sm">
                            <i class="fas fa-play text-[10px]"></i>
                        </div>
                        <span class="font-bold text-secondary text-sm">${timeText}</span>
                    </div>
                `;
                slotBtn.addEventListener('click', function() {
                    selectSlotMobile(slot.id, this);
                });
            } else {
                slotBtn.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-gray-400 group-hover:text-primary transition-colors shadow-sm">
                            <i class="fas fa-play text-[10px]"></i>
                        </div>
                        <span class="font-bold text-secondary text-sm">${timeText}</span>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-primary opacity-0 group-hover:opacity-100 transition-opacity">Select</span>
                `;
                slotBtn.addEventListener('click', function() {
                    selectSlot(slot.id, this);
                });
            }

            container.appendChild(slotBtn);
        });

        if (isMobile) container.className = gridClass;
    }

    function formatTime(time) {
        const [h, m] = time.split(':');
        const hour = parseInt(h);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return hour12 + ':' + m + ' ' + ampm;
    }

    // ============================================================
    // Slot Selection
    // ============================================================
    function selectSlot(id, btn) {
        document.querySelectorAll('#slots-container .slot-btn').forEach(b => {
            b.classList.remove('border-primary', 'bg-white', 'shadow-md');
            b.classList.add('border-gray-50', 'bg-light');
        });
        btn.classList.add('border-primary', 'bg-white', 'shadow-md');
        btn.classList.remove('border-gray-50', 'bg-light');

        selectedSlotId = id;
        document.getElementById('selected-slot-id').value = id;

        const summary = document.getElementById('summary-container');
        summary.classList.remove('opacity-50', 'pointer-events-none');

        const timeText = btn.querySelector('.font-bold').textContent;
        document.getElementById('summary-time').textContent = timeText;
    }

    function selectSlotMobile(id, btn) {
        document.querySelectorAll('#slots-container-mobile .slot-btn-mb').forEach(b => {
            b.classList.remove('border-primary', 'bg-white', 'shadow-card');
            b.classList.add('border-gray-100', 'bg-light');
        });
        btn.classList.add('border-primary', 'bg-white', 'shadow-card');
        btn.classList.remove('border-gray-100', 'bg-light');

        selectedSlotId = id;
        document.getElementById('selected-slot-id-mobile').value = id;

        const summary = document.getElementById('summary-mobile');
        summary.classList.remove('opacity-50', 'pointer-events-none');

        const timeText = btn.querySelector('.font-bold').textContent;
        document.getElementById('summary-time-mobile').textContent = timeText;

        setTimeout(() => summary.scrollIntoView({ behavior: 'smooth', block: 'center' }), 100);
    }

    // ============================================================
    // Init
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        currentWeekIndex = 0;
        renderCalendar('calendar-grid', 'week-label', 0, false);
        renderCalendar('calendar-grid-mobile', 'week-label-mobile', 0, true);
    });
</script>
@endpush
