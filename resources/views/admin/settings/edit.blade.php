@extends('admin.layout')

@section('title', 'Settings')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[70vh] py-8 px-2">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Site Settings</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Manage your status page branding and preferences.</p>
    </div>
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="w-full max-w-2xl space-y-8">
        @if(session('success'))
            <div class="mb-6 p-3 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded shadow-sm border border-green-200 dark:border-green-700 flex items-center gap-2">
                <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                {{ session('success') }}
            </div>
        @endif
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="w-full max-w-2xl space-y-8">
        @csrf
        <!-- General Info -->
        <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-6 space-y-6">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">General Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Site Name</label>
                    <input type="text" name="site_name" value="{{ old('site_name', $site_name ?? '') }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Company Name</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $company_name ?? '') }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Support Email</label>
                    <input type="email" name="support_email" value="{{ old('support_email', $support_email ?? '') }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Support URL</label>
                    <input type="url" name="support_url" value="{{ old('support_url', $support_url ?? '') }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Twitter Handle</label>
                    <input type="text" name="twitter_handle" value="{{ old('twitter_handle', $twitter_handle ?? '') }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                </div>
            </div>
        </div>
        <!-- Logo -->
        <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-6 space-y-4">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Branding</h2>
            <label for="logo-upload" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Site Logo</label>
            <div class="flex items-center gap-6">
                <div class="flex flex-col items-center gap-2">
                    <img id="logo-preview" src="{{ $logo ? asset('storage/' . $logo) : '' }}" alt="Logo Preview" class="h-24 w-24 rounded-full shadow border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 object-contain" style="display: {{ $logo ? 'block' : 'none' }};">
                    @unless($logo)
                        <span id="no-logo" class="text-gray-400 italic">No logo uploaded</span>
                    @endunless
                </div>
                <div>
                    <label for="logo-upload" class="cursor-pointer inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow transition-colors duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5" /></svg>
                        <span>Choose Logo</span>
                    </label>
                    <input id="logo-upload" type="file" name="logo" class="hidden" accept="image/*" onchange="previewLogo(event)">
                    @error('logo')
                        <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <!-- Monitoring -->
        <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-6 space-y-4">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Monitoring</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Default Check Interval (seconds)</label>
                    <input type="number" name="default_check_interval" value="{{ old('default_check_interval', $default_check_interval ?? 300) }}" min="1" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Default Timeout (seconds)</label>
                    <input type="number" name="default_timeout" value="{{ old('default_timeout', $default_timeout ?? 10) }}" min="1" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Min Check Interval (seconds)</label>
                    <input type="number" name="min_check_interval" value="{{ old('min_check_interval', $min_check_interval ?? 60) }}" min="1" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Max Check Interval (seconds)</label>
                    <input type="number" name="max_check_interval" value="{{ old('max_check_interval', $max_check_interval ?? 3600) }}" min="1" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                </div>
            </div>
        </div>
        <!-- Display Options -->
        <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-6 space-y-4">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Display Options</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Show Uptime Percentage</label>
                    <input type="checkbox" name="show_uptime_percentage" value="1" {{ old('show_uptime_percentage', $show_uptime_percentage ?? true) ? 'checked' : '' }}>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Show Response Times</label>
                    <input type="checkbox" name="show_response_times" value="1" {{ old('show_response_times', $show_response_times ?? true) ? 'checked' : '' }}>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Uptime Calculation Days</label>
                    <input type="number" name="uptime_calculation_days" value="{{ old('uptime_calculation_days', $uptime_calculation_days ?? 30) }}" min="1" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Incident Retention Days</label>
                    <input type="number" name="incident_retention_days" value="{{ old('incident_retention_days', $incident_retention_days ?? 90) }}" min="1" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                </div>
            </div>
        </div>
        <!-- Theme Colors -->
        <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-6 space-y-4">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Theme & Appearance</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Primary Color</label>
                    <input type="color" name="primary_color" value="{{ old('primary_color', $primary_color ?? '#3b82f6') }}" class="w-16 h-10 p-0 border-0 bg-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Success Color</label>
                    <input type="color" name="success_color" value="{{ old('success_color', $success_color ?? '#10b981') }}" class="w-16 h-10 p-0 border-0 bg-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Warning Color</label>
                    <input type="color" name="warning_color" value="{{ old('warning_color', $warning_color ?? '#f59e0b') }}" class="w-16 h-10 p-0 border-0 bg-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Danger Color</label>
                    <input type="color" name="danger_color" value="{{ old('danger_color', $danger_color ?? '#ef4444') }}" class="w-16 h-10 p-0 border-0 bg-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Info Color</label>
                    <input type="color" name="info_color" value="{{ old('info_color', $info_color ?? '#06b6d4') }}" class="w-16 h-10 p-0 border-0 bg-transparent">
                </div>
            </div>
            <div class="flex items-center gap-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Dark Mode Enabled</label>
                    <input type="checkbox" name="dark_mode_enabled" value="1" {{ old('dark_mode_enabled', $dark_mode_enabled ?? true) ? 'checked' : '' }}>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Default Dark Mode</label>
                    <input type="checkbox" name="default_dark_mode" value="1" {{ old('default_dark_mode', $default_dark_mode ?? false) ? 'checked' : '' }}>
                </div>
            </div>
        </div>
        <!-- Notifications -->
        <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-6 space-y-4">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Notifications</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Enable Email Notifications</label>
                    <input type="checkbox" name="email_enabled" value="1" {{ old('email_enabled', $email_enabled ?? false) ? 'checked' : '' }}>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Enable Slack Notifications</label>
                    <input type="checkbox" name="slack_enabled" value="1" {{ old('slack_enabled', $slack_enabled ?? false) ? 'checked' : '' }}>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Enable Discord Notifications</label>
                    <input type="checkbox" name="discord_enabled" value="1" {{ old('discord_enabled', $discord_enabled ?? false) ? 'checked' : '' }}>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Discord Webhook URL</label>
                    <input type="url" name="discord_webhook_url" value="{{ old('discord_webhook_url', $discord_webhook_url ?? '') }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                </div>
            </div>
        </div>
        <!-- Auto Refresh -->
        <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-6 space-y-4">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Auto Refresh</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Enable Auto Refresh</label>
                    <input type="checkbox" name="enable_auto_refresh" value="1" {{ old('enable_auto_refresh', $enable_auto_refresh ?? true) ? 'checked' : '' }}>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Auto Refresh Interval (seconds)</label>
                    <input type="number" name="auto_refresh_interval" value="{{ old('auto_refresh_interval', $auto_refresh_interval ?? 30) }}" min="1" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                </div>
            </div>
        </div>
        <!-- Add User (Admins Only) -->
        @if(auth()->user() && auth()->user()->isAdmin())
        <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-6 space-y-4 mt-8">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Add User</h2>
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Name</label>
                        <input type="text" name="name" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Email</label>
                        <input type="email" name="email" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Password</label>
                        <input type="password" name="password" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Role</label>
                        <select name="role" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
                            <option value="administrator">Administrator</option>
                            <option value="service_manager">Service Manager</option>
                            <option value="status_manager">Status Manager</option>
                            <option value="incident_creator">Incident Creator</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-semibold shadow transition-colors duration-200">Add User</button>
                </div>
            </form>
        </div>
        @endif
        <div class="flex justify-end pt-8">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold shadow transition-colors duration-200">Save Settings</button>
        </div>
    </form>
        <script>
        function previewLogo(event) {
            const [file] = event.target.files;
            const preview = document.getElementById('logo-preview');
            const noLogo = document.getElementById('no-logo');
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
                if (noLogo) noLogo.style.display = 'none';
            } else {
                preview.src = '';
                preview.style.display = 'none';
                if (noLogo) noLogo.style.display = 'inline';
            }
        }
        </script>
    </div>
</div>
</div>
@endsection
