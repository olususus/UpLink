@extends('admin.layout')

@section('title', 'Edit Service')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Edit Service: {{ $service->name }}</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.services.show', $service) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    View Service
                </a>
                <a href="{{ route('admin.services.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Services
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.services.update', $service) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Service Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $service->name) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $service->slug) }}" 
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
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $service->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- URL -->
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700">Service URL</label>
                        <input type="url" name="url" id="url" value="{{ old('url', $service->url) }}" 
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
                            <option value="automatic" {{ old('type', $service->type) === 'automatic' ? 'selected' : '' }}>Automatic Monitoring</option>
                            <option value="manual" {{ old('type', $service->type) === 'manual' ? 'selected' : '' }}>Manual Updates</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Current Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Select status</option>
                            <option value="operational" {{ old('status', $service->status) === 'operational' ? 'selected' : '' }}>Operational</option>
                            <option value="degraded" {{ old('status', $service->status) === 'degraded' ? 'selected' : '' }}>Degraded</option>
                            <option value="maintenance" {{ old('status', $service->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="outage" {{ old('status', $service->status) === 'outage' ? 'selected' : '' }}>Outage</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Message -->
                    <div>
                        <label for="status_message" class="block text-sm font-medium text-gray-700">Status Message</label>
                        <input type="text" name="status_message" id="status_message" value="{{ old('status_message', $service->status_message) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-gray-500">Optional message describing current status</p>
                        @error('status_message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Check Interval -->
                    <div>
                        <label for="check_interval" class="block text-sm font-medium text-gray-700">Check Interval (seconds)</label>
                        <input type="number" name="check_interval" id="check_interval" value="{{ old('check_interval', $service->check_interval) }}" 
                               min="60" max="3600" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <p class="mt-1 text-xs text-gray-500">How often to check the service (60-3600 seconds)</p>
                        @error('check_interval')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', $service->is_active) ? 'checked' : '' }}
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
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Last updated: {{ $service->updated_at->format('M j, Y H:i:s') }}
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Service
                </button>
            </div>
        </form>

        <!-- Danger Zone -->
        <div class="mt-8 pt-6 border-t border-red-200">
            <h3 class="text-lg font-medium text-red-900 mb-4">Danger Zone</h3>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-red-900">Delete Service</h4>
                        <p class="text-sm text-red-700">This action cannot be undone. All status checks and related data will be deleted.</p>
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
</script>
@endsection
