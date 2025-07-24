@extends('admin.layout')

@section('title', 'Edit Service')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Edit Service: {{ $service->name }}</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.services.show', $service) }}" class="inline-flex items-center px-4 py-2 border border-blue-600 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-400 dark:hover:bg-gray-700 transition-colors duration-200">
                    View Service
                </a>
                <a href="{{ route('admin.services.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300">
                    Back to Services
                </a>
            </div>
        </div>

        <!-- Current Status Alert -->
        <div class="mb-6 p-4 rounded-lg border-l-4 {{ 
            $service->status === 'operational' ? 'bg-green-50 dark:bg-green-900/20 border-green-400' : (
            $service->status === 'degraded' ? 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-400' : (
            $service->status === 'maintenance' ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-400' : 
            'bg-red-50 dark:bg-red-900/20 border-red-400'
        )) }}">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium {{ 
                        $service->status === 'operational' ? 'text-green-800 dark:text-green-200' : (
                        $service->status === 'degraded' ? 'text-yellow-800 dark:text-yellow-200' : (
                        $service->status === 'maintenance' ? 'text-blue-800 dark:text-blue-200' : 
                        'text-red-800 dark:text-red-200'
                    )) }}">
                        Current Status: {{ ucfirst($service->status) }}
                    </div>
                    @if($service->status_message)
                        <div class="mt-1 text-sm {{ 
                            $service->status === 'operational' ? 'text-green-700 dark:text-green-300' : (
                            $service->status === 'degraded' ? 'text-yellow-700 dark:text-yellow-300' : (
                            $service->status === 'maintenance' ? 'text-blue-700 dark:text-blue-300' : 
                            'text-red-700 dark:text-red-300'
                        )) }}">
                            {{ $service->status_message }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.services.update', $service) }}" class="space-y-8" id="service-edit-form">
            @csrf
            @method('PUT')

            <!-- Basic Configuration -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Basic Configuration</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $service->name) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $service->slug) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Used in URLs and identifiers</p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $service->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL to Monitor</label>
                        <input type="url" name="url" id="url" value="{{ old('url', $service->url) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="check_interval" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check Interval</label>
                        <select name="check_interval" id="check_interval" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="60" {{ old('check_interval', $service->check_interval) == 60 ? 'selected' : '' }}>Every minute</option>
                            <option value="300" {{ old('check_interval', $service->check_interval) == 300 ? 'selected' : '' }}>Every 5 minutes</option>
                            <option value="600" {{ old('check_interval', $service->check_interval) == 600 ? 'selected' : '' }}>Every 10 minutes</option>
                            <option value="1800" {{ old('check_interval', $service->check_interval) == 1800 ? 'selected' : '' }}>Every 30 minutes</option>
                            <option value="3600" {{ old('check_interval', $service->check_interval) == 3600 ? 'selected' : '' }}>Every hour</option>
                        </select>
                        @error('check_interval')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Service Type and Status -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Type</label>
                        <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="automatic" {{ old('type', $service->type) === 'automatic' ? 'selected' : '' }}>Automatic Monitoring</option>
                            <option value="manual" {{ old('type', $service->type) === 'manual' ? 'selected' : '' }}>Manual Updates</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="operational" {{ old('status', $service->status) === 'operational' ? 'selected' : '' }}>Operational</option>
                            <option value="degraded" {{ old('status', $service->status) === 'degraded' ? 'selected' : '' }}>Degraded</option>
                            <option value="maintenance" {{ old('status', $service->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="outage" {{ old('status', $service->status) === 'outage' ? 'selected' : '' }}>Outage</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" 
                                   {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Service is active</span>
                        </label>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status Message -->
                <div class="mt-6">
                    <label for="status_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Message</label>
                    <input type="text" name="status_message" id="status_message" value="{{ old('status_message', $service->status_message) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Optional message describing current status</p>
                    @error('status_message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- HTTP Configuration -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">HTTP Configuration</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="timeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Timeout (seconds)</label>
                        <input type="number" name="timeout" id="timeout" value="{{ old('timeout', $service->timeout ?? 10) }}" min="1" max="60"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('timeout')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Expected Status Codes</label>
                        <div class="space-y-2">
                            @php
                                $expectedCodes = explode(',', old('expected_status_codes', $service->expected_status_codes ?? '200'));
                            @endphp
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="status-code-checkbox" value="200" {{ in_array('200', $expectedCodes) ? 'checked' : '' }}>
                                <span class="ml-2">200 (OK)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="status-code-checkbox" value="201" {{ in_array('201', $expectedCodes) ? 'checked' : '' }}>
                                <span class="ml-2">201 (Created)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="status-code-checkbox" value="301" {{ in_array('301', $expectedCodes) ? 'checked' : '' }}>
                                <span class="ml-2">301 (Redirect)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="status-code-checkbox" value="302" {{ in_array('302', $expectedCodes) ? 'checked' : '' }}>
                                <span class="ml-2">302 (Redirect)</span>
                            </label>
                        </div>
                        <input type="hidden" name="expected_status_codes" id="expected_status_codes" value="{{ old('expected_status_codes', $service->expected_status_codes ?? '200') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Options</label>
                        <div class="space-y-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="follow_redirects" value="1" {{ old('follow_redirects', $service->follow_redirects ?? true) ? 'checked' : '' }}>
                                <span class="ml-2">Follow redirects</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="verify_ssl" value="1" {{ old('verify_ssl', $service->verify_ssl ?? true) ? 'checked' : '' }}>
                                <span class="ml-2">Verify SSL certificate</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Custom Headers Builder -->
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Custom HTTP Headers</label>
                        <button type="button" id="add-header" class="bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-1 rounded">
                            Add Header
                        </button>
                    </div>
                    <div id="headers-container" class="space-y-2">
                        @if($service->http_headers)
                            @foreach($service->http_headers as $key => $value)
                                <div class="flex space-x-2 items-center">
                                    <input type="text" placeholder="Header Name" value="{{ $key }}" class="header-name flex-1 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                    <input type="text" placeholder="Header Value" value="{{ $value }}" class="header-value flex-1 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                    <button type="button" class="remove-header bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm"></button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <input type="hidden" name="http_headers" id="http_headers_json" value="{{ old('http_headers', $service->http_headers ? json_encode($service->http_headers) : '') }}">
                </div>
            </div>

            <!-- Content Validation -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Content Validation</h2>
                
                <!-- Required Text -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Required Text (page must contain these)</label>
                        <button type="button" id="add-required-text" class="bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-1 rounded">
                            Add Text
                        </button>
                    </div>
                    <div id="required-text-container" class="space-y-2">
                        @if($service->content_checks && isset($service->content_checks['required_text']))
                            @foreach($service->content_checks['required_text'] as $text)
                                <div class="flex space-x-2 items-center">
                                    <input type="text" placeholder="Text that must appear on the page" value="{{ $text }}" class="required-text flex-1 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                    <button type="button" class="remove-required-text bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm"></button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Forbidden Text -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Forbidden Text (page must NOT contain these)</label>
                        <button type="button" id="add-forbidden-text" class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded">
                            Add Text
                        </button>
                    </div>
                    <div id="forbidden-text-container" class="space-y-2">
                        @if($service->content_checks && isset($service->content_checks['forbidden_text']))
                            @foreach($service->content_checks['forbidden_text'] as $text)
                                <div class="flex space-x-2 items-center">
                                    <input type="text" placeholder="Text that must NOT appear on the page" value="{{ $text }}" class="forbidden-text flex-1 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                    <button type="button" class="remove-forbidden-text bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm"></button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Response Size Limits -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="min_response_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum Response Size (bytes)</label>
                        <input type="number" id="min_response_size" value="{{ old('min_response_size', $service->content_checks['response_size']['min_bytes'] ?? 0) }}" min="0"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="max_response_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maximum Response Size (bytes)</label>
                        <input type="number" id="max_response_size" value="{{ old('max_response_size', $service->content_checks['response_size']['max_bytes'] ?? 1048576) }}" min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <input type="hidden" name="content_checks" id="content_checks_json" value="{{ old('content_checks', $service->content_checks ? json_encode($service->content_checks) : '') }}">
            </div>

            <!-- SSL Monitoring -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">SSL & Security Monitoring</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="ssl_enabled" {{ old('ssl_enabled', $service->ssl_monitoring['enabled'] ?? false) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Enable SSL monitoring</span>
                        </label>
                    </div>
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="ssl_check_expiry" {{ old('ssl_check_expiry', $service->ssl_monitoring['check_expiry'] ?? false) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Check certificate expiry</span>
                        </label>
                    </div>
                    <div>
                        <label for="ssl_warning_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Warn when expires in (days)</label>
                        <input type="number" id="ssl_warning_days" value="{{ old('ssl_warning_days', $service->ssl_monitoring['expiry_warning_days'] ?? 30) }}" min="1" max="365"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="ssl_verify_cert" {{ old('ssl_verify_cert', $service->ssl_monitoring['verify_certificate'] ?? false) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Verify certificate validity</span>
                        </label>
                    </div>
                </div>
                <input type="hidden" name="ssl_monitoring" id="ssl_monitoring_json" value="{{ old('ssl_monitoring', $service->ssl_monitoring ? json_encode($service->ssl_monitoring) : '') }}">
            </div>

            <!-- Performance & Alerts -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Performance & Alerts</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="response_time_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Response Time Alert (ms)</label>
                        <input type="number" name="response_time_threshold" id="response_time_threshold" value="{{ old('response_time_threshold', $service->response_time_threshold ?? 5000) }}" min="100"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="retry_attempts" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Retry Attempts</label>
                        <select name="retry_attempts" id="retry_attempts" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="0" {{ old('retry_attempts', $service->retry_attempts ?? 3) == 0 ? 'selected' : '' }}>No retries</option>
                            <option value="1" {{ old('retry_attempts', $service->retry_attempts ?? 3) == 1 ? 'selected' : '' }}>1 retry</option>
                            <option value="2" {{ old('retry_attempts', $service->retry_attempts ?? 3) == 2 ? 'selected' : '' }}>2 retries</option>
                            <option value="3" {{ old('retry_attempts', $service->retry_attempts ?? 3) == 3 ? 'selected' : '' }}>3 retries</option>
                            <option value="5" {{ old('retry_attempts', $service->retry_attempts ?? 3) == 5 ? 'selected' : '' }}>5 retries</option>
                        </select>
                    </div>
                    <div>
                        <label for="consecutive_failures_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Failures before alert</label>
                        <select name="consecutive_failures_threshold" id="consecutive_failures_threshold" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="1" {{ old('consecutive_failures_threshold', $service->consecutive_failures_threshold ?? 3) == 1 ? 'selected' : '' }}>After 1 failure</option>
                            <option value="2" {{ old('consecutive_failures_threshold', $service->consecutive_failures_threshold ?? 3) == 2 ? 'selected' : '' }}>After 2 failures</option>
                            <option value="3" {{ old('consecutive_failures_threshold', $service->consecutive_failures_threshold ?? 3) == 3 ? 'selected' : '' }}>After 3 failures</option>
                            <option value="5" {{ old('consecutive_failures_threshold', $service->consecutive_failures_threshold ?? 3) == 5 ? 'selected' : '' }}>After 5 failures</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Authentication -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Authentication</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="auth_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Authentication Type</label>
                        <select id="auth_type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="none" {{ !$service->auth_config || ($service->auth_config['type'] ?? 'none') === 'none' ? 'selected' : '' }}>No authentication</option>
                            <option value="basic" {{ ($service->auth_config['type'] ?? '') === 'basic' ? 'selected' : '' }}>Basic Auth (username/password)</option>
                            <option value="bearer" {{ ($service->auth_config['type'] ?? '') === 'bearer' ? 'selected' : '' }}>Bearer Token</option>
                            <option value="api_key" {{ ($service->auth_config['type'] ?? '') === 'api_key' ? 'selected' : '' }}>API Key</option>
                        </select>
                    </div>
                    <div id="auth-fields" class="{{ !$service->auth_config || ($service->auth_config['type'] ?? 'none') === 'none' ? 'hidden' : 'block' }}">
                        <!-- Auth fields will be populated by JavaScript -->
                    </div>
                </div>
                <input type="hidden" name="auth_config" id="auth_config_json" value="{{ old('auth_config', $service->auth_config ? json_encode($service->auth_config) : '') }}">
            </div>

            <!-- Maintenance Windows -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Maintenance Windows</h2>
                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="enable_maintenance_windows" {{ $service->maintenance_windows ? 'checked' : '' }}>
                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Enable maintenance windows (service won't alert during these times)</span>
                    </label>
                </div>
                <div id="maintenance-windows-section" class="{{ !$service->maintenance_windows ? 'hidden' : 'block' }}">
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Weekly Maintenance Windows</label>
                            <button type="button" id="add-maintenance-window" class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded">
                                Add Window
                            </button>
                        </div>
                        <div id="maintenance-windows-container" class="space-y-3">
                            @if($service->maintenance_windows && isset($service->maintenance_windows['weekly']))
                                @foreach($service->maintenance_windows['weekly'] as $window)
                                    <div class="grid grid-cols-4 gap-4 p-4 border border-gray-300 dark:border-gray-600 rounded">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Day of Week</label>
                                            <select class="maintenance-day mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                                <option value="monday" {{ ($window['day'] ?? '') === 'monday' ? 'selected' : '' }}>Monday</option>
                                                <option value="tuesday" {{ ($window['day'] ?? '') === 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                                                <option value="wednesday" {{ ($window['day'] ?? '') === 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                                                <option value="thursday" {{ ($window['day'] ?? '') === 'thursday' ? 'selected' : '' }}>Thursday</option>
                                                <option value="friday" {{ ($window['day'] ?? '') === 'friday' ? 'selected' : '' }}>Friday</option>
                                                <option value="saturday" {{ ($window['day'] ?? '') === 'saturday' ? 'selected' : '' }}>Saturday</option>
                                                <option value="sunday" {{ ($window['day'] ?? '') === 'sunday' ? 'selected' : '' }}>Sunday</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Time</label>
                                            <input type="time" class="maintenance-start mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" value="{{ $window['start'] ?? '02:00' }}">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time</label>
                                            <input type="time" class="maintenance-end mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" value="{{ $window['end'] ?? '04:00' }}">
                                        </div>
                                        <div class="flex items-end">
                                            <button type="button" class="remove-maintenance bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm">Remove</button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <input type="hidden" name="maintenance_windows" id="maintenance_windows_json" value="{{ old('maintenance_windows', $service->maintenance_windows ? json_encode($service->maintenance_windows) : '') }}">
            </div>

            <!-- Submit Button -->
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Last updated: {{ $service->updated_at->format('M j, Y H:i:s') }}
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.services.show', $service) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded transition-colors duration-300">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition-colors duration-300">
                        Update Service
                    </button>
                </div>
            </div>
        </form>

        <!-- Danger Zone -->
        <div class="mt-8 pt-6 border-t border-red-200 dark:border-red-700">
            <h3 class="text-lg font-medium text-red-900 dark:text-red-100 mb-4">Danger Zone</h3>
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-red-900 dark:text-red-100">Delete Service</h4>
                        <p class="text-sm text-red-700 dark:text-red-300">This action cannot be undone. All status checks and related data will be deleted.</p>
                    </div>
                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to delete this service? This action cannot be undone.')"
                                class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
                            Delete Service
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status codes handling
    const statusCodeCheckboxes = document.querySelectorAll('.status-code-checkbox');
    const statusCodesInput = document.getElementById('expected_status_codes');

    function updateStatusCodes() {
        const checked = Array.from(statusCodeCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        statusCodesInput.value = checked.join(',');
    }

    statusCodeCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateStatusCodes);
    });

    // Headers builder
    let headerCount = 0;
    document.getElementById('add-header').addEventListener('click', function() {
        headerCount++;
        const container = document.getElementById('headers-container');
        const headerDiv = document.createElement('div');
        headerDiv.className = 'flex space-x-2 items-center';
        headerDiv.innerHTML = `
            <input type="text" placeholder="Header Name (e.g., Authorization)" class="header-name flex-1 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
            <input type="text" placeholder="Header Value (e.g., Bearer token123)" class="header-value flex-1 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
            <button type="button" class="remove-header bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm"></button>
        `;
        container.appendChild(headerDiv);

        headerDiv.querySelector('.remove-header').addEventListener('click', function() {
            headerDiv.remove();
            updateHeadersJSON();
        });

        headerDiv.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', updateHeadersJSON);
        });
    });

    // Remove header handler for existing headers
    document.querySelectorAll('.remove-header').forEach(btn => {
        btn.addEventListener('click', function() {
            this.parentElement.remove();
            updateHeadersJSON();
        });
    });

    // Header input handlers for existing headers
    document.querySelectorAll('#headers-container input').forEach(input => {
        input.addEventListener('input', updateHeadersJSON);
    });

    function updateHeadersJSON() {
        const headers = {};
        document.querySelectorAll('#headers-container > div').forEach(div => {
            const name = div.querySelector('.header-name').value.trim();
            const value = div.querySelector('.header-value').value.trim();
            if (name && value) {
                headers[name] = value;
            }
        });
        document.getElementById('http_headers_json').value = Object.keys(headers).length ? JSON.stringify(headers) : '';
    }

    // Required text builder
    document.getElementById('add-required-text').addEventListener('click', function() {
        const container = document.getElementById('required-text-container');
        const textDiv = document.createElement('div');
        textDiv.className = 'flex space-x-2 items-center';
        textDiv.innerHTML = `
            <input type="text" placeholder="Text that must appear on the page" class="required-text flex-1 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
            <button type="button" class="remove-required-text bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm"></button>
        `;
        container.appendChild(textDiv);

        textDiv.querySelector('.remove-required-text').addEventListener('click', function() {
            textDiv.remove();
            updateContentChecksJSON();
        });

        textDiv.querySelector('.required-text').addEventListener('input', updateContentChecksJSON);
    });

    // Remove required text handler for existing items
    document.querySelectorAll('.remove-required-text').forEach(btn => {
        btn.addEventListener('click', function() {
            this.parentElement.remove();
            updateContentChecksJSON();
        });
    });

    // Required text input handlers for existing items
    document.querySelectorAll('.required-text').forEach(input => {
        input.addEventListener('input', updateContentChecksJSON);
    });

    // Forbidden text builder
    document.getElementById('add-forbidden-text').addEventListener('click', function() {
        const container = document.getElementById('forbidden-text-container');
        const textDiv = document.createElement('div');
        textDiv.className = 'flex space-x-2 items-center';
        textDiv.innerHTML = `
            <input type="text" placeholder="Text that must NOT appear on the page" class="forbidden-text flex-1 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
            <button type="button" class="remove-forbidden-text bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm"></button>
        `;
        container.appendChild(textDiv);

        textDiv.querySelector('.remove-forbidden-text').addEventListener('click', function() {
            textDiv.remove();
            updateContentChecksJSON();
        });

        textDiv.querySelector('.forbidden-text').addEventListener('input', updateContentChecksJSON);
    });

    // Remove forbidden text handler for existing items
    document.querySelectorAll('.remove-forbidden-text').forEach(btn => {
        btn.addEventListener('click', function() {
            this.parentElement.remove();
            updateContentChecksJSON();
        });
    });

    // Forbidden text input handlers for existing items
    document.querySelectorAll('.forbidden-text').forEach(input => {
        input.addEventListener('input', updateContentChecksJSON);
    });

    function updateContentChecksJSON() {
        const contentChecks = {};
        
        // Required text
        const requiredTexts = Array.from(document.querySelectorAll('.required-text'))
            .map(input => input.value.trim())
            .filter(text => text);
        if (requiredTexts.length) {
            contentChecks.required_text = requiredTexts;
        }

        // Forbidden text
        const forbiddenTexts = Array.from(document.querySelectorAll('.forbidden-text'))
            .map(input => input.value.trim())
            .filter(text => text);
        if (forbiddenTexts.length) {
            contentChecks.forbidden_text = forbiddenTexts;
        }

        // Response size
        const minSize = parseInt(document.getElementById('min_response_size').value) || 0;
        const maxSize = parseInt(document.getElementById('max_response_size').value) || 1048576;
        if (minSize > 0 || maxSize < 1048576) {
            contentChecks.response_size = {};
            if (minSize > 0) contentChecks.response_size.min_bytes = minSize;
            if (maxSize < 1048576) contentChecks.response_size.max_bytes = maxSize;
        }

        document.getElementById('content_checks_json').value = Object.keys(contentChecks).length ? JSON.stringify(contentChecks) : '';
    }

    // Update content checks when response size changes
    document.getElementById('min_response_size').addEventListener('input', updateContentChecksJSON);
    document.getElementById('max_response_size').addEventListener('input', updateContentChecksJSON);

    // SSL monitoring
    function updateSSLJSON() {
        const sslEnabled = document.getElementById('ssl_enabled').checked;
        if (!sslEnabled) {
            document.getElementById('ssl_monitoring_json').value = '';
            return;
        }

        const ssl = {
            enabled: true,
            check_expiry: document.getElementById('ssl_check_expiry').checked,
            expiry_warning_days: parseInt(document.getElementById('ssl_warning_days').value) || 30,
            verify_certificate: document.getElementById('ssl_verify_cert').checked
        };

        document.getElementById('ssl_monitoring_json').value = JSON.stringify(ssl);
    }

    ['ssl_enabled', 'ssl_check_expiry', 'ssl_verify_cert'].forEach(id => {
        document.getElementById(id).addEventListener('change', updateSSLJSON);
    });
    document.getElementById('ssl_warning_days').addEventListener('input', updateSSLJSON);

    // Authentication
    const authType = document.getElementById('auth_type');
    const authFields = document.getElementById('auth-fields');

    // Populate auth fields on page load
    const currentAuthType = authType.value;
    if (currentAuthType !== 'none') {
        populateAuthFields(currentAuthType);
    }

    authType.addEventListener('change', function() {
        const type = this.value;
        authFields.className = type === 'none' ? 'hidden' : 'block';
        
        if (type === 'none') {
            authFields.innerHTML = '';
            document.getElementById('auth_config_json').value = '';
            return;
        }

        populateAuthFields(type);
    });

    function populateAuthFields(type) {
        let fieldsHTML = '';
        const existingAuth = @json($service->auth_config ?? []);
        
        switch (type) {
            case 'basic':
                fieldsHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                            <input type="text" id="auth_username" value="${existingAuth.username || ''}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                            <input type="password" id="auth_password" value="${existingAuth.password || ''}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        </div>
                    </div>
                `;
                break;
            case 'bearer':
                fieldsHTML = `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bearer Token</label>
                        <input type="text" id="auth_token" value="${existingAuth.token || ''}" placeholder="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    </div>
                `;
                break;
            case 'api_key':
                fieldsHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Header Name</label>
                            <input type="text" id="auth_key" value="${existingAuth.key || ''}" placeholder="X-API-Key" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">API Key Value</label>
                            <input type="text" id="auth_value" value="${existingAuth.value || ''}" placeholder="your-api-key-here" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        </div>
                    </div>
                `;
                break;
        }

        authFields.innerHTML = fieldsHTML;
        
        // Add event listeners for auth fields
        authFields.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', updateAuthJSON);
        });

        updateAuthJSON();
    }

    function updateAuthJSON() {
        const type = authType.value;
        if (type === 'none') {
            document.getElementById('auth_config_json').value = '';
            return;
        }

        let authConfig = { type };

        switch (type) {
            case 'basic':
                const username = document.getElementById('auth_username')?.value.trim();
                const password = document.getElementById('auth_password')?.value.trim();
                if (username && password) {
                    authConfig.username = username;
                    authConfig.password = password;
                }
                break;
            case 'bearer':
                const token = document.getElementById('auth_token')?.value.trim();
                if (token) {
                    authConfig.token = token;
                }
                break;
            case 'api_key':
                const key = document.getElementById('auth_key')?.value.trim();
                const value = document.getElementById('auth_value')?.value.trim();
                if (key && value) {
                    authConfig.key = key;
                    authConfig.value = value;
                }
                break;
        }

        document.getElementById('auth_config_json').value = JSON.stringify(authConfig);
    }

    // Maintenance windows
    document.getElementById('enable_maintenance_windows').addEventListener('change', function() {
        const section = document.getElementById('maintenance-windows-section');
        section.className = this.checked ? 'block' : 'hidden';
        if (!this.checked) {
            document.getElementById('maintenance_windows_json').value = '';
        }
    });

    document.getElementById('add-maintenance-window').addEventListener('click', function() {
        const container = document.getElementById('maintenance-windows-container');
        const windowDiv = document.createElement('div');
        windowDiv.className = 'grid grid-cols-4 gap-4 p-4 border border-gray-300 dark:border-gray-600 rounded';
        windowDiv.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Day of Week</label>
                <select class="maintenance-day mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    <option value="monday">Monday</option>
                    <option value="tuesday">Tuesday</option>
                    <option value="wednesday">Wednesday</option>
                    <option value="thursday">Thursday</option>
                    <option value="friday">Friday</option>
                    <option value="saturday">Saturday</option>
                    <option value="sunday">Sunday</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Time</label>
                <input type="time" class="maintenance-start mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" value="02:00">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time</label>
                <input type="time" class="maintenance-end mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" value="04:00">
            </div>
            <div class="flex items-end">
                <button type="button" class="remove-maintenance bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm">Remove</button>
            </div>
        `;
        container.appendChild(windowDiv);

        windowDiv.querySelector('.remove-maintenance').addEventListener('click', function() {
            windowDiv.remove();
            updateMaintenanceJSON();
        });

        windowDiv.querySelectorAll('select, input').forEach(input => {
            input.addEventListener('change', updateMaintenanceJSON);
        });

        updateMaintenanceJSON();
    });

    // Remove maintenance window handler for existing windows
    document.querySelectorAll('.remove-maintenance').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.grid').remove();
            updateMaintenanceJSON();
        });
    });

    // Maintenance window input handlers for existing windows
    document.querySelectorAll('#maintenance-windows-container select, #maintenance-windows-container input').forEach(input => {
        input.addEventListener('change', updateMaintenanceJSON);
    });

    function updateMaintenanceJSON() {
        if (!document.getElementById('enable_maintenance_windows').checked) {
            document.getElementById('maintenance_windows_json').value = '';
            return;
        }

        const windows = [];
        document.querySelectorAll('#maintenance-windows-container > div').forEach(div => {
            const day = div.querySelector('.maintenance-day').value;
            const start = div.querySelector('.maintenance-start').value;
            const end = div.querySelector('.maintenance-end').value;
            
            if (day && start && end) {
                windows.push({
                    day: day,
                    start: start,
                    end: end,
                    timezone: 'UTC'
                });
            }
        });

        const maintenanceConfig = windows.length ? { weekly: windows } : {};
        document.getElementById('maintenance_windows_json').value = Object.keys(maintenanceConfig).length ? JSON.stringify(maintenanceConfig) : '';
    }

    // Initialize all JSON fields
    updateHeadersJSON();
    updateContentChecksJSON();
    updateSSLJSON();
    updateMaintenanceJSON();
});
</script>
</div>
@endsection
