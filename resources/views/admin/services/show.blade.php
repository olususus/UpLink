@extends('admin.layout')

@section('title', 'Service Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Service: {{ $service->name }}</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.services.edit', $service) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                    Edit Service
                </a>
                <a href="{{ route('admin.services.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300">
                    Back to Services
                </a>
            </div>
        </div>

        <!-- Current Status Alert -->
        <div class="mb-6 p-4 rounded-lg border-l-4 {{ 
            $service->status === 'operational' ? 'bg-green-50 dark:bg-green-900/20 border-green-400' : (
            $service->status === 'degraded' ? 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-400' : (
            $service->status === 'maintenance' ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-400' : 
            'bg-red-50 dark:bg-red-900/20 border-red-400'
        )) }}">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-lg font-medium {{ 
                        $service->status === 'operational' ? 'text-green-800 dark:text-green-200' : (
                        $service->status === 'degraded' ? 'text-yellow-800 dark:text-yellow-200' : (
                        $service->status === 'maintenance' ? 'text-blue-800 dark:text-blue-200' : 
                        'text-red-800 dark:text-red-200'
                    )) }}">
                        {{ 
                            $service->status === 'operational' ? 'Service Operational' : (
                            $service->status === 'degraded' ? 'Service Degraded' : (
                            $service->status === 'maintenance' ? 'Under Maintenance' : 
                            'Service Outage'
                        )) }}
                    </h3>
                    @if($service->status_message)
                        <div class="mt-1 text-sm {{ 
                            $service->status === 'operational' ? 'text-green-700 dark:text-green-300' : (
                            $service->status === 'degraded' ? 'text-yellow-700 dark:text-yellow-300' : (
                            $service->status === 'maintenance' ? 'text-blue-700 dark:text-blue-300' : 
                            'text-red-700 dark:text-red-300'
                        )) }}">
                            {{ $service->status_message }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Service Information Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Basic Information -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Basic Information</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Name</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-600 p-2 rounded">{{ $service->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-600 p-2 rounded">{{ $service->slug }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $service->description ?: 'No description provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL</label>
                        <p class="mt-1 text-sm">
                            @if($service->url)
                                <a href="{{ $service->url }}" target="_blank" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 font-mono bg-gray-100 dark:bg-gray-600 p-2 rounded inline-block">
                                    {{ $service->url }}
                                </a>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">No URL configured</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Configuration -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Configuration</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                            <span class="inline-flex mt-1 px-3 py-1 text-sm font-semibold rounded-full 
                                {{ $service->type === 'automatic' ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300' : 'bg-gray-100 dark:bg-gray-900/50 text-gray-800 dark:text-gray-300' }}">
                                {{ $service->type === 'automatic' ? 'Automatic' : 'Manual' }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <span class="inline-flex mt-1 px-3 py-1 text-sm font-semibold rounded-full 
                                {{ $service->is_active ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' }}">
                                {{ $service->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check Interval</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-600 p-2 rounded">
                            {{ $service->check_interval }} seconds 
                            @if($service->check_interval >= 3600)
                                ({{ round($service->check_interval / 3600, 1) }} hour{{ $service->check_interval >= 7200 ? 's' : '' }})
                            @elseif($service->check_interval >= 60)
                                ({{ round($service->check_interval / 60) }} minute{{ $service->check_interval >= 120 ? 's' : '' }})
                            @endif
                        </p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Timeout</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-600 p-2 rounded">{{ $service->timeout ?? 10 }}s</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expected Codes</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-600 p-2 rounded">{{ $service->expected_status_codes ?? '200-299' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Configuration -->
        @if($service->content_checks || $service->ssl_monitoring || $service->auth_config || $service->maintenance_windows || $service->http_headers)
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Advanced Configuration</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- HTTP Headers -->
                @if($service->http_headers)
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-medium mb-3 text-gray-900 dark:text-gray-100">HTTP Headers</h3>
                    <div class="space-y-2">
                        @foreach($service->http_headers as $key => $value)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $key }}:</span>
                                <span class="text-sm text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Content Validation -->
                @if($service->content_checks)
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-medium mb-3 text-gray-900 dark:text-gray-100">Content Validation</h3>
                    @if(isset($service->content_checks['required_text']))
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Required Text:</h4>
                            <div class="space-y-1">
                                @foreach($service->content_checks['required_text'] as $text)
                                    <span class="inline-block bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-xs px-2 py-1 rounded-full">{{ $text }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if(isset($service->content_checks['forbidden_text']))
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Forbidden Text:</h4>
                            <div class="space-y-1">
                                @foreach($service->content_checks['forbidden_text'] as $text)
                                    <span class="inline-block bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 text-xs px-2 py-1 rounded-full">{{ $text }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if(isset($service->content_checks['response_size']))
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Response Size:</h4>
                            <p class="text-sm text-gray-900 dark:text-gray-100">
                                Min: {{ number_format($service->content_checks['response_size']['min_bytes'] ?? 0) }} bytes, 
                                Max: {{ number_format($service->content_checks['response_size']['max_bytes'] ?? 1048576) }} bytes
                            </p>
                        </div>
                    @endif
                </div>
                @endif

                <!-- SSL Monitoring -->
                @if($service->ssl_monitoring && $service->ssl_monitoring['enabled'])
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-medium mb-3 text-gray-900 dark:text-gray-100">SSL Monitoring</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-700 dark:text-gray-300">SSL Enabled:</span>
                            <span class="text-green-600 dark:text-green-400">Yes</span>
                        </div>
                        @if($service->ssl_monitoring['check_expiry'])
                            <div class="flex justify-between">
                                <span class="text-gray-700 dark:text-gray-300">Check Expiry:</span>
                                <span class="text-green-600 dark:text-green-400">Yes</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700 dark:text-gray-300">Warning Days:</span>
                                <span class="text-gray-900 dark:text-gray-100 font-mono">{{ $service->ssl_monitoring['expiry_warning_days'] ?? 30 }}</span>
                            </div>
                        @endif
                        @if(isset($service->ssl_monitoring['verify_certificate']) && $service->ssl_monitoring['verify_certificate'])
                            <div class="flex justify-between">
                                <span class="text-gray-700 dark:text-gray-300">Verify Certificate:</span>
                                <span class="text-green-600 dark:text-green-400">Yes</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Authentication -->
                @if($service->auth_config && $service->auth_config['type'] !== 'none')
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-medium mb-3 text-gray-900 dark:text-gray-100">Authentication</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-700 dark:text-gray-300">Type:</span>
                            <span class="text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">
                                {{ ucfirst($service->auth_config['type']) }}
                            </span>
                        </div>
                        @if($service->auth_config['type'] === 'basic')
                            <div class="flex justify-between">
                                <span class="text-gray-700 dark:text-gray-300">Username:</span>
                                <span class="text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">{{ $service->auth_config['username'] ?? 'Not set' }}</span>
                            </div>
                        @elseif($service->auth_config['type'] === 'api_key')
                            <div class="flex justify-between">
                                <span class="text-gray-700 dark:text-gray-300">Header:</span>
                                <span class="text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">{{ $service->auth_config['key'] ?? 'Not set' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Performance Settings -->
                @if($service->response_time_threshold || $service->retry_attempts || $service->consecutive_failures_threshold)
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-medium mb-3 text-gray-900 dark:text-gray-100">Performance Settings</h3>
                    <div class="space-y-2 text-sm">
                        @if($service->response_time_threshold)
                            <div class="flex justify-between">
                                <span class="text-gray-700 dark:text-gray-300">Response Time Alert:</span>
                                <span class="text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">{{ $service->response_time_threshold }}ms</span>
                            </div>
                        @endif
                        @if($service->retry_attempts)
                            <div class="flex justify-between">
                                <span class="text-gray-700 dark:text-gray-300">Retry Attempts:</span>
                                <span class="text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">{{ $service->retry_attempts }}</span>
                            </div>
                        @endif
                        @if($service->consecutive_failures_threshold)
                            <div class="flex justify-between">
                                <span class="text-gray-700 dark:text-gray-300">Failure Threshold:</span>
                                <span class="text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">{{ $service->consecutive_failures_threshold }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Maintenance Windows -->
                @if($service->maintenance_windows)
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-medium mb-3 text-gray-900 dark:text-gray-100">Maintenance Windows</h3>
                    @if(isset($service->maintenance_windows['weekly']))
                        <div class="space-y-3">
                            @foreach($service->maintenance_windows['weekly'] as $window)
                                <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded border border-blue-200 dark:border-blue-700">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-blue-900 dark:text-blue-100">{{ ucfirst($window['day']) }}</span>
                                        <span class="text-blue-700 dark:text-blue-300">{{ $window['start'] }} - {{ $window['end'] }} UTC</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Quick Status Update for Manual Services -->
        @if($service->type === 'manual')
        <div class="mb-8 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-6">
            <h3 class="text-lg font-medium text-yellow-900 dark:text-yellow-100 mb-4">Quick Status Update</h3>
            <form method="POST" action="{{ route('services.status', $service) }}" class="flex flex-wrap items-center gap-4">
                @csrf
                @method('PATCH')
                
                <div>
                    <select name="status" class="rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="operational" {{ $service->status === 'operational' ? 'selected' : '' }}>Operational</option>
                        <option value="degraded" {{ $service->status === 'degraded' ? 'selected' : '' }}>Degraded</option>
                        <option value="maintenance" {{ $service->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="outage" {{ $service->status === 'outage' ? 'selected' : '' }}>Outage</option>
                    </select>
                </div>
                
                <div class="flex-1 min-w-0">
                    <input type="text" name="status_message" placeholder="Optional status message" 
                           value="{{ $service->status_message }}"
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300">
                    Update Status
                </button>
            </form>
        </div>
        @endif

        <!-- Timestamps -->
        <div class="mb-8 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Timestamps</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-700 dark:text-gray-300 font-medium">Created:</span>
                    <span class="text-gray-900 dark:text-gray-100 font-mono">{{ $service->created_at->format('M j, Y H:i:s') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700 dark:text-gray-300 font-medium">Last Updated:</span>
                    <span class="text-gray-900 dark:text-gray-100 font-mono">{{ $service->updated_at->format('M j, Y H:i:s') }}</span>
                </div>
            </div>
        </div>

        <!-- Recent Status Checks for Automatic Services -->
        @if($service->type === 'automatic')
        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Recent Status Checks</h3>
            @if($service->statusChecks && $service->statusChecks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-600">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">HTTP Code</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Response Time</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Error</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($service->statusChecks()->latest('checked_at')->limit(10)->get() as $check)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-mono">
                                        {{ $check->checked_at->format('M j, H:i:s') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $check->status === 'operational' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : '' }}
                                            {{ $check->status === 'maintenance' ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300' : '' }}
                                            {{ $check->status === 'degraded' ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300' : '' }}
                                            {{ $check->status === 'outage' ? 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' : '' }}">
                                            {{ 
                                                $check->status === 'operational' ? 'Operational' : (
                                                $check->status === 'degraded' ? 'Degraded' : (
                                                $check->status === 'maintenance' ? 'Maintenance' :
                                                'Outage'
                                            )) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-mono">
                                        @if($check->http_status)
                                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-600 rounded">{{ $check->http_status }}</span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-mono">
                                        @if($check->response_time)
                                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-600 rounded">{{ $check->response_time }}ms</span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-red-600 dark:text-red-400 max-w-xs">
                                        @if($check->error_message)
                                            <span class="truncate block" title="{{ $check->error_message }}">{{ $check->error_message }}</span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($service->statusChecks()->count() > 10)
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Showing last 10 checks. Total: {{ $service->statusChecks()->count() }} checks</p>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 dark:text-gray-500 text-4xl mb-4"></div>
                    <p class="text-gray-500 dark:text-gray-400">No status checks recorded yet.</p>
                    @if($service->type === 'automatic' && $service->is_active)
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">Checks will appear here once monitoring begins.</p>
                    @endif
                </div>
            @endif
        </div>
        @endif
    </div>
</div>
</div>
@endsection
