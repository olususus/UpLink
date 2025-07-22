@extends('admin.layout')

@section('title', 'Create New Service')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
            <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Create New Service</h1>
            <a href="{{ route('admin.services.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Services
            </a>
        </div>

        <form method="POST" action="{{ route('admin.services.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <p class="mt-1 text-xs text-gray-500">Used in URLs and identifiers</p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- URL -->
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service URL</label>
                        <input type="url" name="url" id="url" value="{{ old('url') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-gray-500">URL to monitor (for automatic services)</p>
                        @error('url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Type</label>
                        <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Select type</option>
                            <option value="automatic" {{ old('type') === 'automatic' ? 'selected' : '' }}>Automatic Monitoring</option>
                            <option value="manual" {{ old('type') === 'manual' ? 'selected' : '' }}>Manual Updates</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Initial Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Select status</option>
                            <option value="operational" {{ old('status', 'operational') === 'operational' ? 'selected' : '' }}>Operational</option>
                            <option value="degraded" {{ old('status') === 'degraded' ? 'selected' : '' }}>Degraded</option>
                            <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="outage" {{ old('status') === 'outage' ? 'selected' : '' }}>Outage</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Message -->
                    <div>
                        <label for="status_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Message</label>
                        <input type="text" name="status_message" id="status_message" value="{{ old('status_message') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-gray-500">Optional message describing current status</p>
                        @error('status_message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Check Interval -->
                    <div>
                        <label for="check_interval" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check Interval (seconds)</label>
                        <input type="number" name="check_interval" id="check_interval" 
                               value="{{ old('check_interval', config('status.default_check_interval', 300)) }}" 
                               min="{{ config('status.min_check_interval', 60) }}" 
                               max="{{ config('status.max_check_interval', 3600) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <p class="mt-1 text-xs text-gray-500">How often to check the service ({{ config('status.min_check_interval', 60) }}-{{ config('status.max_check_interval', 3600) }} seconds)</p>
                        @error('check_interval')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Timeout -->
                    <div>
                        <label for="timeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Request Timeout (seconds)</label>
                        <input type="number" name="timeout" id="timeout" 
                               value="{{ old('timeout', config('status.default_timeout', 10)) }}" 
                               min="1" max="60" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-gray-500">How long to wait for a response (1-60 seconds)</p>
                        @error('timeout')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expected Status Codes -->
                    <div>
                        <label for="expected_status_codes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expected Status Codes</label>
                        <input type="text" name="expected_status_codes" id="expected_status_codes" 
                               value="{{ old('expected_status_codes', '200-299') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-gray-500">Comma-separated codes or ranges (e.g., 200-299,301,302)</p>
                        @error('expected_status_codes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Follow Redirects -->
                    <div class="flex items-center">
                        <input type="checkbox" name="follow_redirects" id="follow_redirects" value="1" 
                               {{ old('follow_redirects', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="follow_redirects" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                            Follow redirects
                        </label>
                        @error('follow_redirects')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                            Service is active
                        </label>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Advanced Monitoring Configuration -->
            <div class="mt-8 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Advanced Monitoring (Optional)</h3>
                
                <!-- Error Patterns -->
                <div class="mb-4">
                    <label for="error_patterns" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Error Patterns</label>
                    <div id="error-patterns-container">
                        <div class="error-pattern-row flex items-center mt-2">
                            <input type="text" name="error_patterns[]" 
                                   value="{{ old('error_patterns.0', '') }}" 
                                   placeholder="Error text or /regex/" 
                                   class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <button type="button" onclick="removeErrorPattern(this)" 
                                    class="ml-2 text-red-600 hover:text-red-800 text-sm">Remove</button>
                        </div>
                    </div>
                    <button type="button" onclick="addErrorPattern()" 
                            class="mt-2 text-blue-600 hover:text-blue-800 text-sm">+ Add Error Pattern</button>
                    <p class="mt-1 text-xs text-gray-500">Text patterns or regex (e.g., "error" or "/error.*occurred/i") to detect in response body</p>
                    @error('error_patterns')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- HTTP Headers -->
                <div>
                    <label for="http_headers" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Custom HTTP Headers</label>
                    <div id="http-headers-container">
                        <div class="http-header-row flex items-center mt-2">
                            <input type="text" name="http_headers_keys[]" 
                                   value="" 
                                   placeholder="Header name" 
                                   class="flex-1 mr-2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <input type="text" name="http_headers_values[]" 
                                   value="" 
                                   placeholder="Header value" 
                                   class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <button type="button" onclick="removeHttpHeader(this)" 
                                    class="ml-2 text-red-600 hover:text-red-800 text-sm">Remove</button>
                        </div>
                    </div>
                    <button type="button" onclick="addHttpHeader()" 
                            class="mt-2 text-blue-600 hover:text-blue-800 text-sm">+ Add HTTP Header</button>
                    <p class="mt-1 text-xs text-gray-500">Custom headers to send with requests (e.g., User-Agent, Authorization)</p>
                    @error('http_headers')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex items-center justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create Service
                </button>
            </div>
        </form>

        <!-- Information Box -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-blue-900 mb-2">Service Configuration Tips</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li><strong>Automatic services:</strong> Provide a URL for monitoring. The system will check this URL periodically.</li>
                <li><strong>Manual services:</strong> Status updates must be made manually through the admin panel.</li>
                <li><strong>Check interval:</strong> Only applies to automatic services. Recommended: 300 seconds (5 minutes).</li>
                <li><strong>Slug:</strong> Will be auto-generated from the name, but can be customized.</li>
            </ul>
        </div>
    </div>
</div>

<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
    document.getElementById('slug').value = slug;
});

// Show/hide URL field based on type selection
document.getElementById('type').addEventListener('change', function() {
    const urlField = document.getElementById('url').closest('div');
    const checkIntervalField = document.getElementById('check_interval').closest('div');
    const timeoutField = document.getElementById('timeout').closest('div');
    const statusCodesField = document.getElementById('expected_status_codes').closest('div');
    const redirectsField = document.getElementById('follow_redirects').closest('div');
    const advancedSection = document.querySelector('.bg-gray-50');
    
    if (this.value === 'automatic') {
        urlField.style.display = 'block';
        checkIntervalField.style.display = 'block';
        timeoutField.style.display = 'block';
        statusCodesField.style.display = 'block';
        redirectsField.style.display = 'block';
        advancedSection.style.display = 'block';
        document.getElementById('url').required = true;
    } else if (this.value === 'manual') {
        urlField.style.display = 'block';
        checkIntervalField.style.display = 'none';
        timeoutField.style.display = 'none';
        statusCodesField.style.display = 'none';
        redirectsField.style.display = 'none';
        advancedSection.style.display = 'none';
        document.getElementById('url').required = false;
    } else {
        urlField.style.display = 'block';
        checkIntervalField.style.display = 'block';
        timeoutField.style.display = 'block';
        statusCodesField.style.display = 'block';
        redirectsField.style.display = 'block';
        advancedSection.style.display = 'block';
        document.getElementById('url').required = false;
    }
});

// Initialize the form based on current type selection
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('type').dispatchEvent(new Event('change'));
});

// Error Patterns Management
function addErrorPattern() {
    const container = document.getElementById('error-patterns-container');
    const div = document.createElement('div');
    div.className = 'error-pattern-row flex items-center mt-2';
    div.innerHTML = `
        <input type="text" name="error_patterns[]" 
               placeholder="Error text or /regex/" 
               class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <button type="button" onclick="removeErrorPattern(this)" 
                class="ml-2 text-red-600 hover:text-red-800 text-sm">Remove</button>
    `;
    container.appendChild(div);
}

function removeErrorPattern(button) {
    const container = document.getElementById('error-patterns-container');
    if (container.children.length > 1) {
        button.parentElement.remove();
    }
}

// HTTP Headers Management
function addHttpHeader() {
    const container = document.getElementById('http-headers-container');
    const div = document.createElement('div');
    div.className = 'http-header-row flex items-center mt-2';
    div.innerHTML = `
        <input type="text" name="http_headers_keys[]" 
               placeholder="Header name" 
               class="flex-1 mr-2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <input type="text" name="http_headers_values[]" 
               placeholder="Header value" 
               class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <button type="button" onclick="removeHttpHeader(this)" 
                class="ml-2 text-red-600 hover:text-red-800 text-sm">Remove</button>
    `;
    container.appendChild(div);
}

function removeHttpHeader(button) {
    const container = document.getElementById('http-headers-container');
    if (container.children.length > 1) {
        button.parentElement.remove();
    }
}

// Clean up empty inputs on form submission
document.querySelector('form').addEventListener('submit', function() {
    // Remove empty error pattern inputs
    document.querySelectorAll('input[name="error_patterns[]"]').forEach(input => {
        if (!input.value.trim()) {
            input.remove();
        }
    });
    
    // Remove empty header inputs
    const headerRows = document.querySelectorAll('.http-header-row');
    headerRows.forEach(row => {
        const keyInput = row.querySelector('input[name="http_headers_keys[]"]');
        const valueInput = row.querySelector('input[name="http_headers_values[]"]');
        if (!keyInput.value.trim() && !valueInput.value.trim()) {
            row.remove();
        }
    });
});
</script>
</div>
@endsection
