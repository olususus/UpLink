@extends('admin.layout')

@section('title', 'Advanced Service Configuration')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Advanced Service Configuration</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.services.advanced-create-json') }}" class="inline-flex items-center px-4 py-2 border border-purple-600 text-sm font-medium rounded-md text-purple-600 bg-white hover:bg-purple-50 dark:bg-gray-800 dark:text-purple-400 dark:border-purple-400 dark:hover:bg-gray-700 transition-colors duration-200">
                    JSON Mode (Power Users)
                </a>
                <a href="{{ route('admin.services.create') }}" class="inline-flex items-center px-4 py-2 border border-blue-600 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-400 dark:hover:bg-gray-700 transition-colors duration-200">
                    Simple Create
                </a>
                <a href="{{ route('admin.services.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300">
                    Back to Services
                </a>
            </div>
        </div>

        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100 mb-2">User-Friendly Configuration</h3>
            <p class="text-blue-700 dark:text-blue-300 text-sm">No coding required! Use visual controls to configure comprehensive monitoring with content validation, SSL checks, performance thresholds, and more.</p>
        </div>

        <!-- Quick Templates -->
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <h3 class="text-lg font-medium text-green-900 dark:text-green-100 mb-3">Quick Start Templates</h3>
            <p class="text-green-700 dark:text-green-300 text-sm mb-4">Choose a template to quickly configure common monitoring scenarios:</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button type="button" class="template-btn p-4 border border-green-300 dark:border-green-600 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors duration-200" data-template="website">
                    <div class="text-lg mb-2"></div>
                    <div class="font-medium text-green-900 dark:text-green-100">Standard Website</div>
                    <div class="text-sm text-green-700 dark:text-green-300">Basic HTTP monitoring with SSL checks</div>
                </button>
                <button type="button" class="template-btn p-4 border border-green-300 dark:border-green-600 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors duration-200" data-template="api">
                    <div class="text-lg mb-2"></div>
                    <div class="font-medium text-green-900 dark:text-green-100">REST API</div>
                    <div class="text-sm text-green-700 dark:text-green-300">API monitoring with JSON validation</div>
                </button>
                <button type="button" class="template-btn p-4 border border-green-300 dark:border-green-600 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors duration-200" data-template="ecommerce">
                    <div class="text-lg mb-2"></div>
                    <div class="font-medium text-green-900 dark:text-green-100">E-commerce Site</div>
                    <div class="text-sm text-green-700 dark:text-green-300">Enhanced monitoring for online stores</div>
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.services.store') }}" class="space-y-8" id="advanced-form">
            @csrf

            <!-- Basic Configuration -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Basic Configuration</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL to Monitor</label>
                        <input type="url" name="url" id="url" value="{{ old('url') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <input type="text" name="description" id="description" value="{{ old('description') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="check_interval" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check Interval (seconds)</label>
                        <select name="check_interval" id="check_interval" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="60">Every minute</option>
                            <option value="300" selected>Every 5 minutes</option>
                            <option value="600">Every 10 minutes</option>
                            <option value="1800">Every 30 minutes</option>
                            <option value="3600">Every hour</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- HTTP Configuration -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">HTTP Configuration</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="timeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Timeout (seconds)</label>
                        <input type="number" name="timeout" id="timeout" value="{{ old('timeout', 10) }}" min="1" max="60"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Expected Status Codes</label>
                        <div class="space-y-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="status-code-checkbox" value="200" checked>
                                <span class="ml-2">200 (OK)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="status-code-checkbox" value="201">
                                <span class="ml-2">201 (Created)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="status-code-checkbox" value="301">
                                <span class="ml-2">301 (Redirect)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="status-code-checkbox" value="302">
                                <span class="ml-2">302 (Redirect)</span>
                            </label>
                        </div>
                        <input type="hidden" name="expected_status_codes" id="expected_status_codes" value="200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Options</label>
                        <div class="space-y-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="follow_redirects" value="1" checked>
                                <span class="ml-2">Follow redirects</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="verify_ssl" value="1" checked>
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
                        <!-- Headers will be added here dynamically -->
                    </div>
                    <input type="hidden" name="http_headers" id="http_headers_json">
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
                        <!-- Required text items will be added here -->
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
                        <!-- Forbidden text items will be added here -->
                    </div>
                </div>

                <!-- Response Size Limits -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="min_response_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum Response Size (bytes)</label>
                        <input type="number" id="min_response_size" value="0" min="0"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="max_response_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maximum Response Size (bytes)</label>
                        <input type="number" id="max_response_size" value="1048576" min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <input type="hidden" name="content_checks" id="content_checks_json">
            </div>

            <!-- SSL Monitoring -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">SSL & Security Monitoring</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="ssl_enabled" checked>
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Enable SSL monitoring</span>
                        </label>
                    </div>
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="ssl_check_expiry" checked>
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Check certificate expiry</span>
                        </label>
                    </div>
                    <div>
                        <label for="ssl_warning_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Warn when expires in (days)</label>
                        <input type="number" id="ssl_warning_days" value="30" min="1" max="365"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="ssl_verify_cert" checked>
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Verify certificate validity</span>
                        </label>
                    </div>
                </div>
                <input type="hidden" name="ssl_monitoring" id="ssl_monitoring_json">
            </div>

            <!-- Performance & Alerts -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Performance & Alerts</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="response_time_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Response Time Alert (ms)</label>
                        <input type="number" name="response_time_threshold" id="response_time_threshold" value="{{ old('response_time_threshold', 5000) }}" min="100"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="retry_attempts" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Retry Attempts</label>
                        <select name="retry_attempts" id="retry_attempts" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="0">No retries</option>
                            <option value="1">1 retry</option>
                            <option value="2">2 retries</option>
                            <option value="3" selected>3 retries</option>
                            <option value="5">5 retries</option>
                        </select>
                    </div>
                    <div>
                        <label for="consecutive_failures_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Failures before alert</label>
                        <select name="consecutive_failures_threshold" id="consecutive_failures_threshold" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="1">After 1 failure</option>
                            <option value="2">After 2 failures</option>
                            <option value="3" selected>After 3 failures</option>
                            <option value="5">After 5 failures</option>
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
                            <option value="none">No authentication</option>
                            <option value="basic">Basic Auth (username/password)</option>
                            <option value="bearer">Bearer Token</option>
                            <option value="api_key">API Key</option>
                        </select>
                    </div>
                    <div id="auth-fields" class="hidden">
                        <!-- Auth fields will be shown based on selected type -->
                    </div>
                </div>
                <input type="hidden" name="auth_config" id="auth_config_json">
            </div>

            <!-- Maintenance Windows -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Maintenance Windows</h2>
                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="enable_maintenance_windows">
                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Enable maintenance windows (service won't alert during these times)</span>
                    </label>
                </div>
                <div id="maintenance-windows-section" class="hidden">
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Weekly Maintenance Windows</label>
                            <button type="button" id="add-maintenance-window" class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded">
                                Add Window
                            </button>
                        </div>
                        <div id="maintenance-windows-container" class="space-y-3">
                            <!-- Maintenance windows will be added here -->
                        </div>
                    </div>
                </div>
                <input type="hidden" name="maintenance_windows" id="maintenance_windows_json">
            </div>

            <!-- Hidden fields for JSON data -->
            <input type="hidden" name="type" value="automatic">
            <input type="hidden" name="is_active" value="1">

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.services.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded transition-colors duration-300">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition-colors duration-300">
                    Create Advanced Service
                </button>
            </div>
        </form>
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

    authType.addEventListener('change', function() {
        const type = this.value;
        authFields.className = type === 'none' ? 'hidden' : 'block';
        
        if (type === 'none') {
            authFields.innerHTML = '';
            document.getElementById('auth_config_json').value = '';
            return;
        }

        let fieldsHTML = '';
        switch (type) {
            case 'basic':
                fieldsHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                            <input type="text" id="auth_username" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                            <input type="password" id="auth_password" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        </div>
                    </div>
                `;
                break;
            case 'bearer':
                fieldsHTML = `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bearer Token</label>
                        <input type="text" id="auth_token" placeholder="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    </div>
                `;
                break;
            case 'api_key':
                fieldsHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Header Name</label>
                            <input type="text" id="auth_key" placeholder="X-API-Key" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">API Key Value</label>
                            <input type="text" id="auth_value" placeholder="your-api-key-here" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
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
    });

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

    // Initialize SSL JSON
    updateSSLJSON();

    // Template system
    const templates = {
        website: {
            name: 'Company Website',
            url: 'https://example.com',
            description: 'Main company website monitoring',
            check_interval: 300,
            timeout: 10,
            expected_status_codes: '200,301,302',
            response_time_threshold: 3000,
            ssl_enabled: true,
            ssl_check_expiry: true,
            ssl_warning_days: 30,
            required_text: ['Home', 'About', 'Contact'],
            forbidden_text: ['Error', 'Maintenance', 'Down for maintenance']
        },
        api: {
            name: 'REST API Service',
            url: 'https://api.example.com/health',
            description: 'Main API endpoint monitoring',
            check_interval: 180,
            timeout: 5,
            expected_status_codes: '200',
            response_time_threshold: 1000,
            ssl_enabled: true,
            auth_type: 'bearer',
            headers: {'Accept': 'application/json', 'Content-Type': 'application/json'}
        },
        ecommerce: {
            name: 'E-commerce Store',
            url: 'https://shop.example.com',
            description: 'Online store with cart functionality',
            check_interval: 300,
            timeout: 15,
            expected_status_codes: '200',
            response_time_threshold: 5000,
            ssl_enabled: true,
            ssl_check_expiry: true,
            required_text: ['Add to Cart', 'Checkout', 'Products'],
            forbidden_text: ['Error 500', 'Out of Stock', 'Payment Error', 'Database Error'],
            min_response_size: 5000
        }
    };

    document.querySelectorAll('.template-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const templateName = this.dataset.template;
            const template = templates[templateName];
            
            if (!template) return;

            // Fill basic fields
            if (template.name) document.getElementById('name').value = template.name;
            if (template.url) document.getElementById('url').value = template.url;
            if (template.description) document.getElementById('description').value = template.description;
            if (template.check_interval) document.getElementById('check_interval').value = template.check_interval;
            if (template.timeout) document.getElementById('timeout').value = template.timeout;
            if (template.response_time_threshold) document.getElementById('response_time_threshold').value = template.response_time_threshold;

            // Handle status codes
            if (template.expected_status_codes) {
                const codes = template.expected_status_codes.split(',');
                statusCodeCheckboxes.forEach(cb => {
                    cb.checked = codes.includes(cb.value);
                });
                updateStatusCodes();
            }

            // SSL settings
            if (template.ssl_enabled !== undefined) {
                document.getElementById('ssl_enabled').checked = template.ssl_enabled;
                if (template.ssl_check_expiry !== undefined) {
                    document.getElementById('ssl_check_expiry').checked = template.ssl_check_expiry;
                }
                if (template.ssl_warning_days) {
                    document.getElementById('ssl_warning_days').value = template.ssl_warning_days;
                }
                updateSSLJSON();
            }

            // Auth settings
            if (template.auth_type) {
                document.getElementById('auth_type').value = template.auth_type;
                authType.dispatchEvent(new Event('change'));
            }

            // Headers
            if (template.headers) {
                const container = document.getElementById('headers-container');
                container.innerHTML = '';
                
                Object.entries(template.headers).forEach(([key, value]) => {
                    document.getElementById('add-header').click();
                    const lastHeader = container.lastElementChild;
                    lastHeader.querySelector('.header-name').value = key;
                    lastHeader.querySelector('.header-value').value = value;
                });
                updateHeadersJSON();
            }

            // Content validation
            if (template.required_text) {
                const container = document.getElementById('required-text-container');
                container.innerHTML = '';
                
                template.required_text.forEach(text => {
                    document.getElementById('add-required-text').click();
                    const lastItem = container.lastElementChild;
                    lastItem.querySelector('.required-text').value = text;
                });
            }

            if (template.forbidden_text) {
                const container = document.getElementById('forbidden-text-container');
                container.innerHTML = '';
                
                template.forbidden_text.forEach(text => {
                    document.getElementById('add-forbidden-text').click();
                    const lastItem = container.lastElementChild;
                    lastItem.querySelector('.forbidden-text').value = text;
                });
            }

            if (template.min_response_size) {
                document.getElementById('min_response_size').value = template.min_response_size;
            }

            updateContentChecksJSON();

            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            successDiv.textContent = `${templateName.charAt(0).toUpperCase() + templateName.slice(1)} template applied!`;
            document.body.appendChild(successDiv);
            
            setTimeout(() => {
                successDiv.remove();
            }, 3000);
        });
    });
});
</script>
</div>
@endsection
