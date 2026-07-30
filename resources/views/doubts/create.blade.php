@extends('layouts.student-app')

@section('title', 'Submit a Doubt | ' . \App\Models\Setting::get('platform_name', 'Drumroll'))

@section('content')
<!-- Page Header -->
<div class="bg-secondary text-white py-16 md:py-24 text-center px-4">
    <h1 class="text-4xl md:text-5xl font-black mb-4 fade-up">Submit Your <span class="text-primary">Doubt</span></h1>
    <p class="text-gray-300 max-w-2xl mx-auto fade-up" style="transition-delay: 0.1s">Stuck on a problem? Submit your question before booking a session so our tutors can prepare the best explanation for you.</p>
</div>

<!-- Main Content -->
<section class="py-16 md:py-24 px-4 bg-light min-h-[70vh]">
    <div class="max-w-2xl mx-auto">
        
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-8 p-4 rounded-xl bg-green-50 text-green-600 border border-green-200 font-bold fade-up flex items-center gap-3">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 p-4 rounded-xl bg-red-50 text-red-600 border border-red-200 font-bold fade-up flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i> Please check the form for errors.
            </div>
        @endif

                <div class="bg-white rounded-[2rem] shadow-hover border border-gray-50 p-8 md:p-12 fade-up">

            @auth
            <div class="mb-6 p-4 bg-primary/5 rounded-xl border border-primary/10 flex items-center gap-3">
                <i class="fas fa-id-card text-primary"></i>
                <div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Your Roll Number</p>
                    <p class="text-sm font-black text-secondary">{{ auth()->user()->roll_number ?? 'Not assigned' }}</p>
                </div>
            </div>
            @endauth
            
            <form action="{{ route('student.doubts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Subject Selection -->
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Select Subject *</label>
                    <select name="subject_id" required class="w-full bg-white border @error('subject_id') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-3.5 px-4 text-sm font-medium transition-all shadow-sm text-gray-600">
                        @if($subjects->count() > 0)
                            <option value="">Choose a subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        @else
                            <option value="">No active subjects available</option>
                        @endif
                    </select>
                    @error('subject_id')<p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>@enderror
                </div>

                <!-- Topic Name -->
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Topic Name *</label>
                    <input type="text" name="topic_name" value="{{ old('topic_name') }}" required class="w-full bg-white border @error('topic_name') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-3.5 px-4 text-sm font-medium transition-all shadow-sm" placeholder="e.g., Fractions, Adjectives, Photosynthesis">
                    @error('topic_name')<p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>@enderror
                </div>

                <!-- Doubt Titles -->
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Doubt Title(s) *</label>
                    <div id="doubt-titles-container" class="space-y-3">
                        <div class="doubt-title-row flex gap-2 items-center">
                            <input type="text" name="doubt_titles[]" value="{{ old('doubt_titles.0') }}" required class="w-full bg-white border @error('doubt_titles.0') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-3.5 px-4 text-sm font-medium transition-all shadow-sm" placeholder="Briefly summarize your question">
                            <button type="button" onclick="removeTitle(this)" class="remove-title-btn hidden shrink-0 w-10 h-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition-colors flex items-center justify-center">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="addTitle()" class="mt-3 inline-flex items-center gap-2 text-xs font-bold text-primary hover:text-primary/80 transition-colors">
                        <i class="fas fa-plus-circle"></i> Add Another Doubt
                    </button>
                    @error('doubt_titles')<p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>@enderror
                    @error('doubt_titles.0')<p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>@enderror
                </div>

                <!-- Detailed Description -->
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Detailed Description *</label>
                    <textarea name="description" required rows="5" class="w-full bg-white border @error('description') border-red-500 @else border-gray-200 hover:border-gray-300 @enderror focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-3.5 px-4 text-sm font-medium transition-all shadow-sm resize-none" placeholder="Explain what you are struggling with in detail...">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>@enderror
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Upload File/Image (Optional)</label>
                    <div class="border-2 border-dashed @error('attachment') border-red-500 @else border-gray-200 hover:border-primary/50 @enderror rounded-xl p-6 text-center transition-colors relative">
                        <input type="file" name="attachment" id="file-upload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="updateFileName(this)">
                        <div class="flex flex-col items-center pointer-events-none" id="upload-placeholder">
                            <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-3 text-xl">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <span class="text-sm font-bold text-secondary" id="file-name-display">Upload a picture of your homework or problem</span>
                            <span class="text-xs text-gray-400 mt-1">Optional. Max 5MB (JPG, PNG, PDF)</span>
                        </div>
                    </div>
                    @error('attachment')<p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-black py-4 rounded-full shadow-lg transition-all duration-300 mt-4 flex items-center justify-center gap-2 transform hover:-translate-y-1">
                    Submit & Continue to History <i class="fas fa-arrow-right text-xs"></i>
                </button>
            </form>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function updateFileName(input) {
        const display = document.getElementById('file-name-display');
        if (input.files && input.files[0]) {
            display.textContent = 'Selected: ' + input.files[0].name;
            display.classList.add('text-primary');
        } else {
            display.textContent = 'Upload a picture of your homework or problem';
            display.classList.remove('text-primary');
        }
    }

    function addTitle() {
        const container = document.getElementById('doubt-titles-container');
        const rowCount = container.querySelectorAll('.doubt-title-row').length;
        const newRow = document.createElement('div');
        newRow.className = 'doubt-title-row flex gap-2 items-center';
        newRow.innerHTML = `
            <input type="text" name="doubt_titles[]" required class="w-full bg-white border border-gray-200 hover:border-gray-300 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl py-3.5 px-4 text-sm font-medium transition-all shadow-sm" placeholder="Briefly summarize your question">
            <button type="button" onclick="removeTitle(this)" class="remove-title-btn shrink-0 w-10 h-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition-colors flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newRow);
        updateRemoveButtons();
    }

    function removeTitle(btn) {
        const row = btn.closest('.doubt-title-row');
        row.remove();
        updateRemoveButtons();
    }

    function updateRemoveButtons() {
        const container = document.getElementById('doubt-titles-container');
        const rows = container.querySelectorAll('.doubt-title-row');
        rows.forEach((row, index) => {
            const removeBtn = row.querySelector('.remove-title-btn');
            if (rows.length === 1) {
                removeBtn.classList.add('hidden');
            } else {
                removeBtn.classList.remove('hidden');
            }
        });
    }

    // Initialize remove buttons on page load
    document.addEventListener('DOMContentLoaded', updateRemoveButtons);
</script>
@endpush
