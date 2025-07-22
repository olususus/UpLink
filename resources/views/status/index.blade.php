@extends('layouts.app')

@section('title', '- Status')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Error Message if Database not configured -->
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            ⚠ Setup Required
                        </span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                        <p class="text-xs text-red-600 mt-1">This message will disappear once the database is properly configured.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Overall Status Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @php
                        $statusColor = match($overallStatus) {
                            'operational' => 'bg-green-100 text-green-800',
                            'degraded' => 'bg-yellow-100 text-yellow-800',
                            'maintenance' => 'bg-blue-100 text-blue-800',
                            'outage' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800'
                        };
                        
                        $statusIcon = match($overallStatus) {
                            'operational' => '✓',
                            'degraded' => '⚠',
                            'maintenance' => '🔧',
                            'outage' => '✗',
                            default => '?'
                        };
                        
                        $statusText = match($overallStatus) {
                            'operational' => 'All Systems Operational',
                            'degraded' => 'Degraded Performance',
                            'maintenance' => 'Under Maintenance',
                            'outage' => 'Service Disruption',
                            default => 'Unknown Status'
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium {{ $statusColor }}">
                        {{ $statusIcon }} {{ $statusText }}
                    </span>
                </div>
                <div class="ml-5 w-0 flex-1">
                    @if($overallStatus === 'operational')
                        <p class="text-sm text-gray-500">All services are running normally.</p>
                    @else
                        <p class="text-sm text-gray-500">Some services are experiencing issues. Check individual service status below.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Active Incidents -->
    @if($activeIncidents->count() > 0)
        <div class="bg-red-50 border border-red-200 rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-red-900 mb-4">Active Incidents</h3>
                <div class="space-y-4">
                    @foreach($activeIncidents as $incident)
                        <div class="bg-white border border-red-200 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $incident->title }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $incident->description }}</p>
                                    <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                        <span>{{ $incident->service?->name ?? 'Unknown Service' }}</span>
                                        <span>{{ $incident->impact_text ?? 'Minor' }} Impact</span>
                                        <span>{{ $incident->started_at?->diffForHumans() ?? 'Unknown time' }}</span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $incident->status_text ?? 'Unknown' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Services Status -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Services</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Current status of all monitored services.</p>
        </div>
        <ul class="divide-y divide-gray-200">
            @forelse($services as $service)
                <li class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @php
                                    $statusColor = match($service->status ?? 'unknown') {
                                        'operational' => 'bg-green-500',
                                        'degraded' => 'bg-yellow-500',
                                        'maintenance' => 'bg-blue-500',
                                        'outage' => 'bg-red-500',
                                        default => 'bg-gray-500'
                                    };
                                @endphp
                                <div class="h-2.5 w-2.5 rounded-full {{ $statusColor }}"></div>
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center">
                                    <p class="text-sm font-medium text-gray-900">{{ $service->name ?? 'Unknown Service' }}</p>
                                    @if(($service->type ?? '') === 'automatic')
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            Auto
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">{{ $service->description ?? 'No description available' }}</p>
                                @if($service->status_message ?? false)
                                    <p class="text-xs text-gray-400 mt-1">{{ $service->status_message }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center">
                            @php
                                $statusBadgeColor = match($service->status ?? 'unknown') {
                                    'operational' => 'bg-green-100 text-green-800',
                                    'degraded' => 'bg-yellow-100 text-yellow-800',
                                    'maintenance' => 'bg-blue-100 text-blue-800',
                                    'outage' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadgeColor }}">
                                {{ $service->status_text ?? 'Unknown' }}
                            </span>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-8 text-center">
                    <div class="text-gray-500">
                        <p class="text-lg font-medium">No services configured yet</p>
                        <p class="text-sm mt-1">Please run the database migrations and seeder to set up the monitoring services:</p>
                        <p class="text-xs mt-2 font-mono bg-gray-100 px-2 py-1 rounded">php artisan migrate --seed</p>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Past Incidents -->
    @if($pastIncidents->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Past Incidents</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Recent resolved incidents and maintenance.</p>
            </div>
            <ul class="divide-y divide-gray-200">
                @foreach($pastIncidents as $incident)
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $incident->title }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $incident->description }}</p>
                                <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                    <span>{{ $incident->service?->name ?? 'Unknown Service' }}</span>
                                    <span>{{ $incident->impact_text ?? 'Minor' }} Impact</span>
                                    <span>Duration: {{ $incident->duration ?? 'Unknown' }}</span>
                                    <span>Resolved {{ $incident->resolved_at?->diffForHumans() ?? 'Unknown time' }}</span>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Resolved
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<script>
    // Auto-refresh page every 5 minutes
    setTimeout(function() {
        window.location.reload();
    }, 300000);
</script>
@endsection
