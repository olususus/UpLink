@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-600">Monitor and manage your status page services</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <span class="text-white font-bold">S</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Services</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalServices }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <span class="text-white font-bold">!</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Incidents</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $activeIncidents }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <span class="text-white font-bold">I</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Incidents</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalIncidents }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <span class="text-white font-bold">✓</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Operational</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $services->where('status', 'operational')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Services Overview -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Services Overview</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Current status of all services</p>
                </div>
                <a href="{{ route('admin.services.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Manage Services →
                </a>
            </div>
            <ul class="divide-y divide-gray-200">
                @foreach($services as $service)
                    <li class="px-4 py-4">
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
                                <div class="h-2.5 w-2.5 rounded-full {{ $statusColor }} mr-3"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $service->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $service->type }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900">{{ $service->status_text }}</p>
                                <p class="text-xs text-gray-500">{{ $service->incidents_count }} incidents</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Recent Status Checks -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Status Checks</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Latest automatic monitoring results</p>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse($recentChecks as $check)
                    <li class="px-4 py-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $check->service->name }}</p>
                                <p class="text-xs text-gray-500">{{ $check->checked_at->format('M d, H:i') }}</p>
                            </div>
                            <div class="text-right">
                                @php
                                    $statusColor = match($check->status) {
                                        'operational' => 'text-green-600',
                                        'degraded' => 'text-yellow-600',
                                        'maintenance' => 'text-blue-600',
                                        'outage' => 'text-red-600',
                                        default => 'text-gray-600'
                                    };
                                @endphp
                                <p class="text-sm {{ $statusColor }}">{{ ucfirst($check->status) }}</p>
                                @if($check->response_time)
                                    <p class="text-xs text-gray-500">{{ $check->response_time }}ms</p>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-3">
                        <p class="text-sm text-gray-500">No status checks yet</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <div class="flex space-x-4">
                <a href="{{ route('admin.incidents.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    Report Incident
                </a>
                <a href="{{ route('admin.services.create') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Add Service
                </a>
                <button onclick="window.location.reload()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Refresh Status
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
