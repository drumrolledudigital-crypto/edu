<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - {{ \App\Models\Setting::get('platform_name', 'Drumroll') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: { 50: '#ecf7fc', 100: '#d0edf8', 200: '#a6dcf1', 300: '#6cc5e7', 400: '#3aabdb', 500: '#2596be', 600: '#1f7aa0', 700: '#1c6283', 800: '#1b526c', 900: '#1b455b', 950: '#112c3d' }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f8fafc;
            color: #111827;
        }
        .login-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2.5px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .field-error { border-color: #ef4444 !important; }
        .shake {
            animation: shake 0.4s ease-in-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-6px); }
            40% { transform: translateX(6px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }
        .success-check {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: #10b981;
            border-radius: 50%;
            animation: popIn 0.3s ease-out;
        }
        @keyframes popIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary-200">
                <i data-lucide="graduation-cap" class="w-7 h-7"></i>
            </div>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold tracking-tight text-gray-900">
            Admin Portal
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Sign in to manage your learning platform.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="login-card py-8 px-4 rounded-[2rem] sm:px-10">
            <!-- Server-side flash messages (non-AJAX fallback) -->
            @if(session('success'))
            <div class="mb-4 p-3 bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs font-bold rounded-xl flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                {{ session('success') }}
            </div>
            @endif

            <!-- AJAX Alert Area -->
            <div id="ajax-alert" class="mb-4 hidden rounded-xl text-xs font-bold flex items-center gap-2 p-3"></div>

            <!-- Success Overlay -->
            <div id="success-overlay" class="hidden text-center py-6">
                <div class="success-check mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <p class="text-sm font-bold text-gray-700" id="success-text">Login successful!</p>
                <p class="text-xs text-gray-400 mt-1">Redirecting to dashboard...</p>
            </div>

            <form id="login-form" class="space-y-6" action="{{ route('admin.login.post') }}" method="POST" novalidate>
                @csrf
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-widest">
                        Email Address
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-gray-50/50 transition-colors">
                    </div>
                    <p id="email-error" class="text-rose-500 text-[11px] font-bold mt-1 hidden"></p>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-widest">
                        Password
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-gray-50/50 transition-colors">
                    </div>
                    <p id="password-error" class="text-rose-500 text-[11px] font-bold mt-1 hidden"></p>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded cursor-pointer">
                        <label for="remember" class="ml-2 block text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer">
                            Remember me
                        </label>
                    </div>

                    <div class="text-xs font-bold">
                        <span class="text-gray-400 uppercase tracking-wider">
                            Contact support to reset password
                        </span>
                    </div>
                </div>

                <div>
                    <button id="login-btn" type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-full shadow-lg text-sm font-black text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed">
                        <span id="btn-text">Sign In</span>
                        <span id="btn-spinner" class="spinner hidden"></span>
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100">
                <div class="text-center">
                    <a href="{{ route('home') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] hover:text-primary-600 transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="arrow-left" class="w-3 h-3"></i>
                        Back to Website
                    </a>
                </div>
            </div>
        </div>
        
        <p class="mt-8 text-center text-[10px] text-gray-400 font-bold uppercase tracking-[0.3em]">
            &copy; {{ date('Y') }} {{ \App\Models\Setting::get('platform_name', 'Drumroll') }} Education Portal
        </p>
    </div>

    <script>
        lucide.createIcons();

        const form = document.getElementById('login-form');
        const loginBtn = document.getElementById('login-btn');
        const btnText = document.getElementById('btn-text');
        const btnSpinner = document.getElementById('btn-spinner');
        const alertBox = document.getElementById('ajax-alert');
        const successOverlay = document.getElementById('success-overlay');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const emailError = document.getElementById('email-error');
        const passwordError = document.getElementById('password-error');
        let isSubmitting = false;

        function clearErrors() {
            emailInput.classList.remove('field-error');
            passwordInput.classList.remove('field-error');
            emailError.classList.add('hidden');
            passwordError.classList.add('hidden');
            alertBox.classList.add('hidden');
        }

        function showFieldError(field, errorEl, message) {
            field.classList.add('field-error');
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
        }

        function showAlert(type, message) {
            alertBox.className = 'mb-4 rounded-xl text-xs font-bold flex items-center gap-2 p-3';
            if (type === 'error') {
                alertBox.classList.add('bg-rose-50', 'border', 'border-rose-100', 'text-rose-600');
            } else {
                alertBox.classList.add('bg-emerald-50', 'border', 'border-emerald-100', 'text-emerald-600');
            }
            alertBox.textContent = message;
            alertBox.classList.remove('hidden');
        }

        function setLoading(loading) {
            isSubmitting = loading;
            loginBtn.disabled = loading;
            btnSpinner.classList.toggle('hidden', !loading);
            btnText.textContent = loading ? 'Signing In...' : 'Sign In';
        }

        function validateClient() {
            let valid = true;
            clearErrors();
            if (!emailInput.value.trim()) {
                showFieldError(emailInput, emailError, 'Email is required');
                valid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
                showFieldError(emailInput, emailError, 'Please enter a valid email');
                valid = false;
            }
            if (!passwordInput.value) {
                showFieldError(passwordInput, passwordError, 'Password is required');
                valid = false;
            }
            return valid;
        }

        emailInput.addEventListener('input', () => {
            emailInput.classList.remove('field-error');
            emailError.classList.add('hidden');
        });

        passwordInput.addEventListener('input', () => {
            passwordInput.classList.remove('field-error');
            passwordError.classList.add('hidden');
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            if (isSubmitting) return;
            clearErrors();

            if (!validateClient()) {
                form.classList.add('shake');
                setTimeout(() => form.classList.remove('shake'), 400);
                return;
            }

            setLoading(true);

            const formData = new FormData(form);
            const csrfToken = formData.get('_token');

            try {
                const response = await fetch('{{ route("admin.login.post") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    setLoading(false);
                    form.classList.add('hidden');
                    alertBox.classList.add('hidden');
                    successOverlay.classList.remove('hidden');
                    document.getElementById('success-text').textContent = data.message || 'Login successful!';
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1200);
                } else {
                    setLoading(false);
                    if (response.status === 422 && data.errors) {
                        if (data.errors.email) {
                            showFieldError(emailInput, emailError, data.errors.email[0]);
                        }
                        if (data.errors.password) {
                            showFieldError(passwordInput, passwordError, data.errors.password[0]);
                        }
                    } else if (response.status === 419) {
                        showAlert('error', 'Session expired. Please refresh the page and try again.');
                    } else if (response.status === 429) {
                        showAlert('error', 'Too many login attempts. Please wait a moment and try again.');
                    } else {
                        showAlert('error', data.message || 'Login failed. Please try again.');
                        passwordInput.value = '';
                        passwordInput.focus();
                    }
                    form.classList.add('shake');
                    setTimeout(() => form.classList.remove('shake'), 400);
                }
            } catch (err) {
                setLoading(false);
                showAlert('error', 'Network error. Please check your connection and try again.');
                passwordInput.value = '';
                passwordInput.focus();
                form.classList.add('shake');
                setTimeout(() => form.classList.remove('shake'), 400);
            }
        });

        form.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !isSubmitting) {
                form.dispatchEvent(new Event('submit'));
            }
        });
    </script>
</body>
</html>
