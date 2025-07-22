@extends('layouts.app')

@section('title', '- Status')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Error Message if Database not configured -->
    @if(isset($error) || session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg mb-6 transition-colors duration-300">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-200">
                            ⚠ Setup Required
                        </span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <p class="text-sm text-red-700 dark:text-red-300">{{ $error ?? session('error') }}</p>
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">This message will disappear once the database is properly configured.</p>
                        <div class="mt-3">
                            <p class="text-xs text-red-600 dark:text-red-400 font-mono">Run: php artisan migrate --seed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Overall Status Header -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg mb-6 transition-colors duration-300">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex-shrink-0">
                    @php
                        $statusColor = match($overallStatus) {
                            'operational' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                            'degraded' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                            'maintenance' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
                            'outage' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
                            default => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'
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
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium {{ $statusColor }} transition-colors duration-300">
                        {{ $statusIcon }} {{ $statusText }}
                    </span>
                </div>
                <div class="ml-5 w-0 flex-1">
                    @if($overallStatus === 'operational')
                        <p class="text-sm text-gray-500 dark:text-gray-400">All services are running normally.</p>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Some services are experiencing issues. Check individual service status below.</p>
                    @endif
                </div>
                
                <!-- Support Contact -->
                @if(config('status.support_email'))
                    <div class="flex-shrink-0">
                        <a href="mailto:{{ config('status.support_email') }}" 
                           class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Contact Support
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Active Incidents -->
    @if($activeIncidents->count() > 0)
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg mb-6 transition-colors duration-300">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-red-900 dark:text-red-200 mb-4">Active Incidents</h3>
                <div class="space-y-4">
                    @foreach($activeIncidents as $incident)
                        <div class="bg-white dark:bg-gray-800 border border-red-200 dark:border-red-700 rounded-lg p-4 transition-colors duration-300">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $incident->title }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $incident->description }}</p>
                                    <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                        <span>{{ $incident->service?->name ?? 'Unknown Service' }}</span>
                                        <span>{{ $incident->impact_text ?? 'Minor' }} Impact</span>
                                        <span>{{ $incident->started_at?->diffForHumans() ?? 'Unknown time' }}</span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-200">
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
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md mb-6 transition-colors duration-300">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Services</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Current status of all monitored services.</p>
        </div>
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
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
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $service->name ?? 'Unknown Service' }}</p>
                                    @if(($service->type ?? '') === 'automatic')
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            Auto
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $service->description ?? 'No description available' }}</p>
                                @if($service->status_message ?? false)
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $service->status_message }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center">
                            @php
                                $statusBadgeColor = match($service->status ?? 'unknown') {
                                    'operational' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                                    'degraded' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                                    'maintenance' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
                                    'outage' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
                                    default => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'
                                };
                            @endphp
                            
                            @if(config('status.show_uptime_percentage', false))
                                @php
                                    $uptime = $service->getUptimePercentage(30);
                                    $uptimeColor = $uptime >= 99.9 ? 'text-green-600 dark:text-green-400' : ($uptime >= 99 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400');
                                @endphp
                                <div class="text-right mr-4">
                                    <div class="text-sm font-medium {{ $uptimeColor }}">{{ $uptime }}%</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">30-day uptime</div>
                                </div>
                            @endif
                            
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadgeColor }} transition-colors duration-300">
                                {{ $service->status_text ?? 'Unknown' }}
                            </span>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-8 text-center">
                    <div class="text-gray-500 dark:text-gray-400">
                        <p class="text-lg font-medium">No services configured yet</p>
                        <p class="text-sm mt-1">Please run the database migrations and seeder to set up the monitoring services:</p>
                        <p class="text-xs mt-2 font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">php artisan migrate --seed</p>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Past Incidents -->
    @if($pastIncidents->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md transition-colors duration-300">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Past Incidents</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Recent resolved incidents and maintenance.</p>
            </div>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($pastIncidents as $incident)
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $incident->title }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $incident->description }}</p>
                                <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                    <span>{{ $incident->service?->name ?? 'Unknown Service' }}</span>
                                    <span>{{ $incident->impact_text ?? 'Minor' }} Impact</span>
                                    <span>Duration: {{ $incident->duration ?? 'Unknown' }}</span>
                                    <span>Resolved {{ $incident->resolved_at?->diffForHumans() ?? 'Unknown time' }}</span>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200">
                                Resolved
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

@if(config('status.enable_auto_refresh', true))
<script>
    // Auto-refresh page based on configuration
    const refreshInterval = {{ config('status.auto_refresh_interval', 30) }} * 1000; // Convert to milliseconds
    
    let countdown = refreshInterval / 1000;
    let refreshTimer;
    
    function updateCountdown() {
        const statusElement = document.querySelector('.refresh-countdown');
        if (statusElement) {
            statusElement.textContent = `Auto-refresh in ${countdown}s`;
        }
        
        if (countdown <= 0) {
            window.location.reload();
        } else {
            countdown--;
        }
    }
    
    // Add refresh indicator
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.querySelector('.bg-white.overflow-hidden.shadow.rounded-lg.mb-6 .px-4.py-5.sm\\:p-6');
        if (header) {
            const refreshDiv = document.createElement('div');
            refreshDiv.className = 'mt-2 text-xs text-gray-500 dark:text-gray-400';
            refreshDiv.innerHTML = '<span class="refresh-countdown">Auto-refresh in ' + (refreshInterval/1000) + 's</span> | <button onclick="clearInterval(refreshTimer)" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">Disable auto-refresh</button>';
            header.appendChild(refreshDiv);
        }
        
        // Start countdown
        refreshTimer = setInterval(updateCountdown, 1000);
    });
</script>
@endif

<!-- Uptime Display -->
@if(config('status.show_uptime_percentage', true))
<script>
    // Add uptime percentages to service cards
    document.addEventListener('DOMContentLoaded', function() {
        // This would calculate and display uptime percentages
        // Implementation would require additional backend calculations
    });
</script>
@endif
@endsection
