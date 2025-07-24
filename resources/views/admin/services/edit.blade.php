@extends('admin.layout')

@section('title', 'Edit Service')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
                        </h3>
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
                        @if($service->last_checked_at)
                            <div class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                                Last checked: {{ $service->last_checked_at->diffForHumans() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.services.update', $service) }}" class="space-y-6">
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
                            <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL to Monitor</label>
                            <input type="url" name="url" id="url" value="{{ old('url', $service->url) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('url')
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
                    </div>

                    <!-- Service Settings -->
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

                    <!-- Status Message and Check Interval -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="status_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Message</label>
                            <input type="text" name="status_message" id="status_message" value="{{ old('status_message', $service->status_message) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Optional message describing current status</p>
                            @error('status_message')
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
                </div>

                <!-- Content Validation (only for automatic services) -->
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600" id="content-validation-section">
                    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Content Validation</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Forbidden Text -->
                        <div>
                            <label for="forbidden_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Forbidden Text</label>
                            <textarea name="forbidden_text" id="forbidden_text" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Enter text that should NOT appear (one per line)">{{ old('forbidden_text', is_array($service->content_checks['forbidden_text'] ?? null) ? implode("\n", $service->content_checks['forbidden_text']) : '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Service will be marked as degraded if this text is found</p>
                        </div>

                        <!-- Required Text -->
                        <div>
                            <label for="required_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Required Text</label>
                            <textarea name="required_text" id="required_text" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Enter text that MUST appear (one per line)">{{ old('required_text', is_array($service->content_checks['required_text'] ?? null) ? implode("\n", $service->content_checks['required_text']) : '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Service will be marked as degraded if this text is missing</p>
                        </div>
                    </div>
                </div>

                <!-- HTTP Configuration (only for automatic services) -->
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600" id="http-config-section">
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
                            <label for="expected_status_codes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expected Status Codes</label>
                            <input type="text" name="expected_status_codes" id="expected_status_codes" value="{{ old('expected_status_codes', $service->expected_status_codes ?? '200-299') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="200-299, 200,201,204">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">e.g., 200-299 or 200,201,204</p>
                            @error('expected_status_codes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="ssl_verify" id="ssl_verify" value="1" 
                                       {{ old('ssl_verify', $service->ssl_verify ?? true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Verify SSL Certificate</span>
                            </label>
                            @error('ssl_verify')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Custom Headers -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Custom HTTP Headers</label>
                        <div id="headers-container">
                            @php
                                $headers = old('headers', $service->headers ?? []);
                            @endphp
                            @if(!empty($headers))
                                @foreach($headers as $key => $value)
                                <div class="header-row flex gap-3 mb-3">
                                    <input type="text" name="headers[{{ $loop->index }}][key]" value="{{ $key }}" placeholder="Header Name" 
                                           class="flex-1 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <input type="text" name="headers[{{ $loop->index }}][value]" value="{{ $value }}" placeholder="Header Value" 
                                           class="flex-1 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <button type="button" class="remove-header bg-red-500 hover:bg-red-700 text-white px-3 py-2 rounded"></button>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" id="add-header" class="mt-2 bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">Add Header</button>
                    </div>
                </div>

                <!-- Performance & Monitoring (only for automatic services) -->
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600" id="performance-section">
                    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Performance & Monitoring</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="response_time_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Response Time Threshold (ms)</label>
                            <input type="number" name="response_time_threshold" id="response_time_threshold" 
                                   value="{{ old('response_time_threshold', $service->response_time_threshold ?? 5000) }}" min="100" max="30000"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Service marked as degraded if response time exceeds this</p>
                            @error('response_time_threshold')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="retry_attempts" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Retry Attempts</label>
                            <select name="retry_attempts" id="retry_attempts" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="0" {{ old('retry_attempts', $service->retry_attempts ?? 1) == 0 ? 'selected' : '' }}>No retries</option>
                                <option value="1" {{ old('retry_attempts', $service->retry_attempts ?? 1) == 1 ? 'selected' : '' }}>1 retry</option>
                                <option value="2" {{ old('retry_attempts', $service->retry_attempts ?? 1) == 2 ? 'selected' : '' }}>2 retries</option>
                                <option value="3" {{ old('retry_attempts', $service->retry_attempts ?? 1) == 3 ? 'selected' : '' }}>3 retries</option>
                            </select>
                            @error('retry_attempts')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="consecutive_failures_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Failure Threshold</label>
                            <select name="consecutive_failures_threshold" id="consecutive_failures_threshold" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="1" {{ old('consecutive_failures_threshold', $service->consecutive_failures_threshold ?? 2) == 1 ? 'selected' : '' }}>1 failure</option>
                                <option value="2" {{ old('consecutive_failures_threshold', $service->consecutive_failures_threshold ?? 2) == 2 ? 'selected' : '' }}>2 consecutive failures</option>
                                <option value="3" {{ old('consecutive_failures_threshold', $service->consecutive_failures_threshold ?? 2) == 3 ? 'selected' : '' }}>3 consecutive failures</option>
                                <option value="5" {{ old('consecutive_failures_threshold', $service->consecutive_failures_threshold ?? 2) == 5 ? 'selected' : '' }}>5 consecutive failures</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Failures needed before marking as outage</p>
                            @error('consecutive_failures_threshold')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SSL & Security Monitoring (only for automatic services) -->
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600" id="ssl-section">
                    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">SSL & Security Monitoring</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="ssl_enabled" id="ssl_enabled" value="1" 
                                       {{ old('ssl_enabled', $service->ssl_monitoring['enabled'] ?? true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Enable SSL monitoring</span>
                            </label>
                        </div>

                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="ssl_check_expiry" id="ssl_check_expiry" value="1" 
                                       {{ old('ssl_check_expiry', $service->ssl_monitoring['check_expiry'] ?? true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Check certificate expiry</span>
                            </label>
                        </div>

                        <div>
                            <label for="ssl_warning_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Warn when expires in (days)</label>
                            <input type="number" name="ssl_warning_days" id="ssl_warning_days" 
                                   value="{{ old('ssl_warning_days', $service->ssl_monitoring['warning_days'] ?? 30) }}" min="1" max="365"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Authentication (only for automatic services) -->
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600" id="auth-section">
                    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Authentication</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="auth_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Authentication Type</label>
                            <select name="auth_type" id="auth_type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="none" {{ old('auth_type', $service->auth_config['type'] ?? 'none') == 'none' ? 'selected' : '' }}>No authentication</option>
                                <option value="basic" {{ old('auth_type', $service->auth_config['type'] ?? 'none') == 'basic' ? 'selected' : '' }}>Basic Auth (username/password)</option>
                                <option value="bearer" {{ old('auth_type', $service->auth_config['type'] ?? 'none') == 'bearer' ? 'selected' : '' }}>Bearer Token</option>
                                <option value="api_key" {{ old('auth_type', $service->auth_config['type'] ?? 'none') == 'api_key' ? 'selected' : '' }}>API Key</option>
                            </select>
                        </div>
                        <div id="auth-fields">
                            @php
                                $authType = old('auth_type', $service->auth_config['type'] ?? 'none');
                            @endphp
                            @if($authType == 'basic')
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                                        <input type="text" name="auth_username" value="{{ old('auth_username', $service->auth_config['username'] ?? '') }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                                        <input type="password" name="auth_password" value="{{ old('auth_password', $service->auth_config['password'] ?? '') }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                    </div>
                                </div>
                            @elseif($authType == 'bearer')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bearer Token</label>
                                    <input type="text" name="auth_token" value="{{ old('auth_token', $service->auth_config['token'] ?? '') }}" 
                                           placeholder="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                </div>
                            @elseif($authType == 'api_key')
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Header Name</label>
                                        <input type="text" name="auth_key" value="{{ old('auth_key', $service->auth_config['key'] ?? '') }}" 
                                               placeholder="X-API-Key"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">API Key Value</label>
                                        <input type="text" name="auth_value" value="{{ old('auth_value', $service->auth_config['value'] ?? '') }}" 
                                               placeholder="your-api-key-here"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Maintenance Windows (only for automatic services) -->
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600" id="maintenance-section">
                    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Maintenance Windows</h2>
                    
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="enable_maintenance_windows" id="enable_maintenance_windows" value="1" 
                                   {{ old('enable_maintenance_windows', !empty($service->maintenance_windows)) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Enable maintenance windows (service won't alert during these times)</span>
                        </label>
                    </div>

                    <div id="maintenance-windows-section" class="{{ old('enable_maintenance_windows', !empty($service->maintenance_windows)) ? 'block' : 'hidden' }}">
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Weekly Maintenance Windows</label>
                                <button type="button" id="add-maintenance-window" class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded">
                                    Add Window
                                </button>
                            </div>
                            <div id="maintenance-windows-container" class="space-y-3">
                                @php
                                    $maintenanceWindows = old('maintenance_windows', $service->maintenance_windows ?? []);
                                @endphp
                                @if(!empty($maintenanceWindows))
                                    @foreach($maintenanceWindows as $index => $window)
                                    <div class="grid grid-cols-4 gap-4 p-4 border border-gray-300 dark:border-gray-600 rounded maintenance-window">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Day of Week</label>
                                            <select name="maintenance_windows[{{ $index }}][day]" class="maintenance-day mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                                <option value="monday" {{ $window['day'] == 'monday' ? 'selected' : '' }}>Monday</option>
                                                <option value="tuesday" {{ $window['day'] == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                                                <option value="wednesday" {{ $window['day'] == 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                                                <option value="thursday" {{ $window['day'] == 'thursday' ? 'selected' : '' }}>Thursday</option>
                                                <option value="friday" {{ $window['day'] == 'friday' ? 'selected' : '' }}>Friday</option>
                                                <option value="saturday" {{ $window['day'] == 'saturday' ? 'selected' : '' }}>Saturday</option>
                                                <option value="sunday" {{ $window['day'] == 'sunday' ? 'selected' : '' }}>Sunday</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Time</label>
                                            <input type="time" name="maintenance_windows[{{ $index }}][start]" value="{{ $window['start'] }}" 
                                                   class="maintenance-start mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time</label>
                                            <input type="time" name="maintenance_windows[{{ $index }}][end]" value="{{ $window['end'] }}" 
                                                   class="maintenance-end mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                        </div>
                                        <div class="flex items-end">
                                            <button type="button" class="remove-maintenance-window bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm w-full">Remove</button>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>

            <!-- Danger Zone -->
            <div class="mt-8 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-red-900 dark:text-red-200 mb-4">Danger Zone</h3>
                <p class="text-sm text-red-700 dark:text-red-300 mb-4">
                    Deleting this service will remove all associated incidents and status checks. This action cannot be undone.
                </p>
                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" 
                      onsubmit="return confirm('Are you sure you want to delete this service? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300">
                        Delete Service
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const automaticSections = ['content-validation-section', 'http-config-section', 'performance-section', 'ssl-section', 'auth-section', 'maintenance-section'];
    let headerIndex = {{ count(old('headers', $service->headers ?? [])) }};
    let maintenanceIndex = {{ count(old('maintenance_windows', $service->maintenance_windows ?? [])) }};
    
    function toggleAutomaticSections() {
        const isAutomatic = typeSelect.value === 'automatic';
        automaticSections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            if (section) {
                section.style.display = isAutomatic ? 'block' : 'none';
            }
        });
    }
    
    typeSelect.addEventListener('change', toggleAutomaticSections);
    toggleAutomaticSections(); // Run on page load

    // Add header functionality
    document.getElementById('add-header').addEventListener('click', function() {
        const container = document.getElementById('headers-container');
        const headerRow = document.createElement('div');
        headerRow.className = 'header-row flex gap-3 mb-3';
        headerRow.innerHTML = `
            <input type="text" name="headers[${headerIndex}][key]" placeholder="Header Name" 
                   class="flex-1 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <input type="text" name="headers[${headerIndex}][value]" placeholder="Header Value" 
                   class="flex-1 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <button type="button" class="remove-header bg-red-500 hover:bg-red-700 text-white px-3 py-2 rounded"></button>
        `;
        container.appendChild(headerRow);
        headerIndex++;
        
        // Add remove functionality to the new button
        headerRow.querySelector('.remove-header').addEventListener('click', function() {
            headerRow.remove();
        });
    });

    // Add remove functionality to existing headers
    document.querySelectorAll('.remove-header').forEach(button => {
        button.addEventListener('click', function() {
            button.closest('.header-row').remove();
        });
    });

    // Authentication type switching
    const authType = document.getElementById('auth_type');
    const authFields = document.getElementById('auth-fields');
    
    authType.addEventListener('change', function() {
        const type = this.value;
        let fieldsHTML = '';
        
        switch (type) {
            case 'basic':
                fieldsHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                            <input type="text" name="auth_username" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                            <input type="password" name="auth_password" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        </div>
                    </div>
                `;
                break;
            case 'bearer':
                fieldsHTML = `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bearer Token</label>
                        <input type="text" name="auth_token" placeholder="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    </div>
                `;
                break;
            case 'api_key':
                fieldsHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Header Name</label>
                            <input type="text" name="auth_key" placeholder="X-API-Key" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">API Key Value</label>
                            <input type="text" name="auth_value" placeholder="your-api-key-here" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        </div>
                    </div>
                `;
                break;
        }
        
        authFields.innerHTML = fieldsHTML;
    });

    // Maintenance windows
    document.getElementById('enable_maintenance_windows').addEventListener('change', function() {
        const section = document.getElementById('maintenance-windows-section');
        section.className = this.checked ? 'block' : 'hidden';
    });

    document.getElementById('add-maintenance-window').addEventListener('click', function() {
        const container = document.getElementById('maintenance-windows-container');
        const windowDiv = document.createElement('div');
        windowDiv.className = 'grid grid-cols-4 gap-4 p-4 border border-gray-300 dark:border-gray-600 rounded maintenance-window';
        windowDiv.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Day of Week</label>
                <select name="maintenance_windows[${maintenanceIndex}][day]" class="maintenance-day mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
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
                <input type="time" name="maintenance_windows[${maintenanceIndex}][start]" value="02:00" 
                       class="maintenance-start mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time</label>
                <input type="time" name="maintenance_windows[${maintenanceIndex}][end]" value="04:00" 
                       class="maintenance-end mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
            </div>
            <div class="flex items-end">
                <button type="button" class="remove-maintenance-window bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm w-full">Remove</button>
            </div>
        `;
        container.appendChild(windowDiv);
        maintenanceIndex++;
        
        // Add remove functionality to the new button
        windowDiv.querySelector('.remove-maintenance-window').addEventListener('click', function() {
            windowDiv.remove();
        });
    });

    // Add remove functionality to existing maintenance windows
    document.querySelectorAll('.remove-maintenance-window').forEach(button => {
        button.addEventListener('click', function() {
            button.closest('.maintenance-window').remove();
        });
    });
});
</script>

@endsection
