@extends('admin.layout')

@section('title', 'Services')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Services Management</h1>
            <p class="text-gray-600">Manage and monitor your services</p>
        </div>
        <a href="{{ route('admin.services.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            Add Service
        </a>
    </div>

    <!-- Services List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @foreach($services as $service)
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                @php
                                    $statusColor = match($service->status) {
                                        'operational' => 'bg-green-500',
                                        'degraded' => 'bg-yellow-500',
                                        'maintenance' => 'bg-blue-500',
                                        'outage' => 'bg-red-500',
                                        default => 'bg-gray-500'
                                    };
                                @endphp
                                <div class="h-3 w-3 rounded-full {{ $statusColor }} mr-4"></div>
                                <div>
                                    <div class="flex items-center">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $service->name }}</h3>
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $service->type === 'automatic' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($service->type) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $service->description }}</p>
                                    @if($service->url)
                                        <p class="text-xs text-gray-500">{{ $service->url }}</p>
                                    @endif
                                    @if($service->status_message)
                                        <p class="text-xs text-gray-600 mt-1">{{ $service->status_message }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                @if($service->type === 'manual')
                                    <!-- Manual Status Update Form -->
                                    <form action="{{ route('admin.services.status', $service) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="text-sm border-gray-300 rounded-md">
                                            <option value="operational" {{ $service->status === 'operational' ? 'selected' : '' }}>Operational</option>
                                            <option value="degraded" {{ $service->status === 'degraded' ? 'selected' : '' }}>Degraded</option>
                                            <option value="maintenance" {{ $service->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                            <option value="outage" {{ $service->status === 'outage' ? 'selected' : '' }}>Outage</option>
                                        </select>
                                        <button type="submit" class="text-sm px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                            Update
                                        </button>
                                    </form>
                                @else
                                    <span class="text-sm px-3 py-1 {{ $service->status === 'operational' ? 'bg-green-100 text-green-800' : ($service->status === 'outage' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }} rounded-md">
                                        {{ $service->status_text }}
                                    </span>
                                @endif
                                
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.services.show', $service) }}" class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                                    <a href="{{ route('admin.services.edit', $service) }}" class="text-gray-600 hover:text-gray-800 text-sm">Edit</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Service Stats -->
                        <div class="mt-2 flex items-center text-xs text-gray-500 space-x-4">
                            <span>{{ $service->incidents_count }} incidents</span>
                            <span>{{ $service->status_checks_count }} checks</span>
                            @if($service->type === 'automatic')
                                <span>Every {{ $service->check_interval / 60 }} minutes</span>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
