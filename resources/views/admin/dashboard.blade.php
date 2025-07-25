@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Admin Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400">Monitor and manage your status page services</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition-colors duration-300 transform hover:scale-[1.025] hover:shadow-xl hover:z-10">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <span class="text-white font-bold">S</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Services</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $totalServices }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition-colors duration-300 transform hover:scale-[1.025] hover:shadow-xl hover:z-10">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <span class="text-white font-bold">!</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Incidents</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $activeIncidents }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition-colors duration-300 transform hover:scale-[1.025] hover:shadow-xl hover:z-10">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <span class="text-white font-bold">I</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Incidents</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $totalIncidents }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition-colors duration-300 transform hover:scale-[1.025] hover:shadow-xl hover:z-10">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <span class="text-white font-bold"></span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Operational</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $services->where('status', 'operational')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg transition-colors duration-300 transform hover:scale-[1.01] hover:shadow-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Services Overview</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Current status of all services</p>
                </div>
                <a href="{{ route('admin.services.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                    Manage Services →
                </a>
            </div>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
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
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $service->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $service->type }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $service->status_text }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $service->incidents_count }} incidents</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg transition-colors duration-300 transform hover:scale-[1.01] hover:shadow-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Recent Status Checks</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Latest automatic monitoring results</p>
            </div>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($recentChecks as $check)
                    <li class="px-4 py-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $check->service->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $check->checked_at->format('M d, H:i') }}</p>
                            </div>
                            <div class="text-right">
                                @php
                                    $statusColor = match($check->status) {
                                        'operational' => 'text-green-600 dark:text-green-400',
                                        'degraded' => 'text-yellow-600 dark:text-yellow-400',
                                        'maintenance' => 'text-blue-600 dark:text-blue-400',
                                        'outage' => 'text-red-600 dark:text-red-400',
                                        default => 'text-gray-600 dark:text-gray-400'
                                    };
                                @endphp
                                <p class="text-sm {{ $statusColor }}">{{ ucfirst($check->status) }}</p>
                                @if($check->response_time)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $check->response_time }}ms</p>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-3">
                        <p class="text-sm text-gray-500 dark:text-gray-400">No status checks yet</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="mt-6 bg-white dark:bg-gray-800 shadow rounded-lg transition-colors duration-300 transform hover:scale-[1.01] hover:shadow-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Quick Actions</h3>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:px-6">
            <div class="flex space-x-4">
                <a href="{{ route('admin.incidents.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-red-400 focus:ring-offset-2 focus:outline-none transition-all duration-200 transform hover:scale-105 active:scale-95">
                    <span class="relative">Report Incident</span>
                </a>
                <a href="{{ route('admin.services.create') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:outline-none transition-all duration-200 transform hover:scale-105 active:scale-95">
                    <span class="relative">Add Service</span>
                </a>
                <button onclick="window.location.reload()" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:outline-none transition-all duration-200 transform hover:scale-105 active:scale-95">
                    <span class="relative">Refresh Status</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
