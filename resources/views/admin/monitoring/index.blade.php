@extends('admin.layout')

@section('title', 'Monitoring Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Monitoring Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400">Monitor service health and run manual checks</p>
    </div>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">🔧 Manual Testing Tools</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <h3 class="font-semibold mb-3 text-gray-900 dark:text-gray-100">Test Maintenance API</h3>
                        <div class="space-y-3">
                            <select id="testServiceSelect" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                @foreach($services as $service)
                                    @if($service->url)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button onclick="testMaintenanceAPI()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full transition-colors duration-200">
                                Test Maintenance API
                            </button>
                        </div>
                    </div>

                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <h3 class="font-semibold mb-3 text-gray-900 dark:text-gray-100">Run Manual Check</h3>
                        <div class="space-y-3">
                            <select id="checkServiceSelect" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                @foreach($services->where('type', 'automatic') as $service)
                                    @if($service->url)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button onclick="runManualCheck()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full transition-colors duration-200">
                                Run Check & Update Status
                            </button>
                        </div>
                    </div>
                </div>

                <div id="testResults" class="mt-6 hidden">
                    <h3 class="font-semibold mb-3 text-gray-900 dark:text-gray-100">Test Results</h3>
                <div class="bg-gray-100 rounded-lg p-4">
                    <pre id="testOutput" class="text-sm overflow-x-auto whitespace-pre-wrap"></pre>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">📊 Current Service Status</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($services as $service)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $service->name }}</h3>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $service->status === 'operational' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : '' }}
                                {{ $service->status === 'maintenance' ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300' : '' }}
                                {{ $service->status === 'degraded' ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300' : '' }}
                                {{ $service->status === 'outage' ? 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' : '' }}">
                                {{ ucfirst($service->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $service->status_message ?? 'No status message' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Updated: {{ $service->updated_at->diffForHumans() }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">📋 Recent Status Checks (Last 50)</h2>
                <button onclick="location.reload()" class="bg-gray-500 hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-500 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                    Refresh
                </button>
            </div>

            @if($recentChecks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Service</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">HTTP</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Response Time</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Error</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentChecks as $check)
                                <tr class="{{ $loop->iteration <= 10 ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $check->checked_at->format('M j, H:i:s') }}
                                        @if($loop->iteration <= 10)
                                            <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded bg-blue-100 text-blue-800 ml-2">Latest 10</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $check->service->name ?? 'Unknown' }}
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
    </div>
</div>

<script>
async function testMaintenanceAPI() {
    const serviceId = document.getElementById('testServiceSelect').value;
    const resultsDiv = document.getElementById('testResults');
    const outputPre = document.getElementById('testOutput');
    
    resultsDiv.classList.remove('hidden');
    outputPre.textContent = 'Testing...';
    
    try {
        const response = await fetch(`{{ route('admin.monitoring.test-api') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ service_id: serviceId })
        });
        
        const data = await response.json();
        outputPre.textContent = JSON.stringify(data, null, 2);
    } catch (error) {
        outputPre.textContent = 'Error: ' + error.message;
    }
}

async function runManualCheck() {
    const serviceId = document.getElementById('checkServiceSelect').value;
    const resultsDiv = document.getElementById('testResults');
    const outputPre = document.getElementById('testOutput');
    
    resultsDiv.classList.remove('hidden');
    outputPre.textContent = 'Running manual check...';
    
    try {
        const response = await fetch(`{{ route('admin.monitoring.manual-check') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ service_id: serviceId })
        });
        
        const data = await response.json();
        outputPre.textContent = JSON.stringify(data, null, 2);
        
        // Refresh page after 2 seconds to show updated status
        if (data.success) {
            setTimeout(() => location.reload(), 2000);
        }
    } catch (error) {
        outputPre.textContent = 'Error: ' + error.message;
    }
}
</script>
@endsection
