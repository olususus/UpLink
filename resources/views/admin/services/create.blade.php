@extends('admin.layout')

@section('title', 'Create New Service')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
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
                        <label for="name" class="block text-sm font-medium text-gray-700">Service Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <p class="mt-1 text-xs text-gray-500">Used in URLs and identifiers</p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- URL -->
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700">Service URL</label>
                        <input type="url" name="url" id="url" value="{{ old('url') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                        <label for="type" class="block text-sm font-medium text-gray-700">Service Type</label>
                        <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
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
                        <label for="status" class="block text-sm font-medium text-gray-700">Initial Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
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
                        <label for="status_message" class="block text-sm font-medium text-gray-700">Status Message</label>
                        <input type="text" name="status_message" id="status_message" value="{{ old('status_message') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-gray-500">Optional message describing current status</p>
                        @error('status_message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Check Interval -->
                    <div>
                        <label for="check_interval" class="block text-sm font-medium text-gray-700">Check Interval (seconds)</label>
                        <input type="number" name="check_interval" id="check_interval" value="{{ old('check_interval', 300) }}" 
                               min="60" max="3600" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <p class="mt-1 text-xs text-gray-500">How often to check the service (60-3600 seconds)</p>
                        @error('check_interval')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Service is active
                        </label>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
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
    
    if (this.value === 'automatic') {
        urlField.style.display = 'block';
        checkIntervalField.style.display = 'block';
        document.getElementById('url').required = true;
    } else if (this.value === 'manual') {
        urlField.style.display = 'block';
        checkIntervalField.style.display = 'none';
        document.getElementById('url').required = false;
    } else {
        urlField.style.display = 'block';
        checkIntervalField.style.display = 'block';
        document.getElementById('url').required = false;
    }
});

// Initialize the form based on current type selection
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('type').dispatchEvent(new Event('change'));
});
</script>
@endsection
