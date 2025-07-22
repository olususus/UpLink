<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Custom Theme Colors -->
        <style>
            :root {
                --color-primary: {{ config('status.theme.primary_color', '#3b82f6') }};
                --color-success: {{ config('status.theme.success_color', '#10b981') }};
                --color-warning: {{ config('status.theme.warning_color', '#f59e0b') }};
                --color-danger: {{ config('status.theme.danger_color', '#ef4444') }};
                --color-info: {{ config('status.theme.info_color', '#06b6d4') }};
            }
            
            .bg-primary { background-color: var(--color-primary) !important; }
            .text-primary { color: var(--color-primary) !important; }
            .border-primary { border-color: var(--color-primary) !important; }
            
            .bg-status-operational { background-color: var(--color-success) !important; }
            .bg-status-degraded { background-color: var(--color-warning) !important; }
            .bg-status-outage { background-color: var(--color-danger) !important; }
            .bg-status-maintenance { background-color: var(--color-info) !important; }
        </style>

        <!-- Dark Mode Script -->
        <script>
            // Check for saved theme preference or default to system preference
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && {{ config('status.dark_mode.default_dark_mode', 'false') ? 'true' : 'false' }})) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow transition-colors duration-300">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <div class="text-gray-900 dark:text-white">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
            
            <!-- Footer with company info -->
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-8 transition-colors duration-300">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
                        <div>
                            <p>&copy; {{ date('Y') }} {{ config('status.company_name', config('app.name')) }}. All rights reserved.</p>
                        </div>
                        <div class="flex space-x-4">
                            @if(config('status.support_url'))
                                <a href="{{ config('status.support_url') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">Support</a>
                            @endif
                            @if(config('status.support_email'))
                                <a href="mailto:{{ config('status.support_email') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">Contact</a>
                            @endif
                            @if(config('status.twitter_handle'))
                                <a href="https://twitter.com/{{ config('status.twitter_handle') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">Twitter</a>
                            @endif
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
