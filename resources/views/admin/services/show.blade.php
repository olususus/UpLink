@extends('admin.layout')

@section('title', 'Service Details')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Service: {{ $service->name }}</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.services.edit', $service) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Service
                </a>
                <a href="{{ route('admin.services.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Services
                </a>
            </div>
        </div>

        <!-- Service Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $service->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Slug</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $service->slug }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $service->description ?? 'No description provided' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">URL</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if($service->url)
                            <a href="{{ $service->url }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                {{ $service->url }}
                            </a>
                        @else
                            No URL configured
                        @endif
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Status</label>
                    <span class="inline-flex mt-1 px-3 py-1 text-sm font-semibold rounded-full 
                        {{ $service->status === 'operational' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $service->status === 'maintenance' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $service->status === 'degraded' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $service->status === 'outage' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($service->status) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Message</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $service->status_message ?? 'No status message' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Type</label>
                    <span class="inline-flex mt-1 px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $service->type === 'automatic' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($service->type) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Check Interval</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $service->check_interval }} seconds</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Active</label>
                    <span class="inline-flex mt-1 px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $service->is_active ? 'Yes' : 'No' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="border-t border-gray-200 pt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Timestamps</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                <div>
                    <strong>Created:</strong> {{ $service->created_at->format('M j, Y H:i:s') }}
                </div>
                <div>
                    <strong>Last Updated:</strong> {{ $service->updated_at->format('M j, Y H:i:s') }}
                </div>
            </div>
        </div>

        <!-- Quick Status Update -->
        @if($service->type === 'manual')
        <div class="border-t border-gray-200 pt-6 mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Status Update</h3>
            <form method="POST" action="{{ route('admin.services.status', $service) }}" class="flex items-center space-x-4">
                @csrf
                @method('PATCH')
                
                <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="operational" {{ $service->status === 'operational' ? 'selected' : '' }}>Operational</option>
                    <option value="degraded" {{ $service->status === 'degraded' ? 'selected' : '' }}>Degraded</option>
                    <option value="maintenance" {{ $service->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="outage" {{ $service->status === 'outage' ? 'selected' : '' }}>Outage</option>
                </select>
                
                <input type="text" name="status_message" placeholder="Optional status message" 
                       value="{{ $service->status_message }}"
                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Update Status
                </button>
            </form>
        </div>
        @endif

        <!-- Recent Status Checks -->
        @if($service->type === 'automatic')
        <div class="border-t border-gray-200 pt-6 mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Status Checks</h3>
            @if($service->statusChecks && $service->statusChecks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HTTP Code</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Response Time</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Error</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($service->statusChecks()->latest('checked_at')->limit(10)->get() as $check)
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $check->checked_at->format('M j, H:i:s') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $check->status === 'operational' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $check->status === 'maintenance' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $check->status === 'degraded' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $check->status === 'outage' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($check->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $check->http_status ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $check->response_time ? $check->response_time . 'ms' : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-red-600 max-w-xs truncate">
                                        {{ $check->error_message ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No status checks recorded yet.</p>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
