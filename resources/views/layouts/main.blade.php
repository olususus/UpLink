<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'UpLink')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --color-primary: {{ config('status.theme.primary_color', '#3b82f6') }};
            --color-success: {{ config('status.theme.success_color', '#10b981') }};
            --color-warning: {{ config('status.theme.warning_color', '#f59e0b') }};
            --color-danger: {{ config('status.theme.danger_color', '#ef4444') }};
            --color-info: {{ config('status.theme.info_color', '#06b6d4') }};
        }
    </style>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && {{ config('status.dark_mode.default_dark_mode', 'false') ? 'true' : 'false' }})) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-300 min-h-screen flex flex-col">
    @include('layouts.navigation')
    <main class="flex-1">
        @yield('content')
    </main>
</main>
    <!-- Alpine.js for dropdowns and interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
