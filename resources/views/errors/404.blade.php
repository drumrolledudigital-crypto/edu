<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found | {{ \App\Models\Setting::get('platform_name', 'Drumroll') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#ecf7fc', 100: '#d0edf8', 200: '#a6dcf1', 300: '#6cc5e7', 400: '#3aabdb', 500: '#2596be', 600: '#1f7aa0', 700: '#1c6283', 800: '#1b526c', 900: '#1b455b', 950: '#112c3d' }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />
</head>
<body class="font-sans bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="text-center px-6">
        <div class="w-20 h-20 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="text-4xl font-black text-primary-600">404</span>
        </div>
        <h1 class="text-3xl font-black text-gray-900 mb-3">Page Not Found</h1>
        <p class="text-gray-500 mb-8 max-w-md mx-auto">The page you're looking for doesn't exist or has been moved.</p>
        <a href="/" class="inline-block bg-primary-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-primary-700 transition-colors shadow-sm">Go Home</a>
    </div>
</body>
</html>
