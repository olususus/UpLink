@extends('admin.layout')

@section('title', 'Analytics')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Service Analytics</h1>
        <p class="text-gray-600 dark:text-gray-400">Uptime, response times, and incident frequency for all monitored services</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Uptime Percentages</h2>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Service</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">30d Uptime</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $service->name }}</td>
                            <td class="px-4 py-2 text-sm font-mono {{ $service->getUptimePercentage(30) >= 99.9 ? 'text-green-600 dark:text-green-400' : ($service->getUptimePercentage(30) >= 99 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                {{ $service->getUptimePercentage(30) }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Average Response Times</h2>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Service</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Avg. Response (ms)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $service->name }}</td>
                            <td class="px-4 py-2 text-sm font-mono text-blue-600 dark:text-blue-400">
                                {{ $service->statusChecks()->where('checked_at', '>=', now()->subDays(30))->avg('response_time') ? number_format($service->statusChecks()->where('checked_at', '>=', now()->subDays(30))->avg('response_time'), 2) : 'N/A' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Incident Frequency (Last 30 Days)</h2>
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Service</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Incidents</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $service->name }}</td>
                        <td class="px-4 py-2 text-sm font-mono text-red-600 dark:text-red-400">
                            {{ $service->incidents()->where('created_at', '>=', now()->subDays(30))->count() }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Charts</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <canvas id="uptimeChart"></canvas>
            </div>
            <div>
                <canvas id="incidentChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Uptime Chart
        const uptimeCtx = document.getElementById('uptimeChart').getContext('2d');
        new Chart(uptimeCtx, {
            type: 'bar',
            data: {
                labels: @json($services->pluck('name')),
                datasets: [{
                    label: 'Uptime % (30d)',
                    data: @json($services->map(fn($s) => $s->getUptimePercentage(30))),
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, max: 100 }
                }
            }
        });
        // Incident Chart
        const incidentCtx = document.getElementById('incidentChart').getContext('2d');
        new Chart(incidentCtx, {
            type: 'bar',
            data: {
                labels: @json($services->pluck('name')),
                datasets: [{
                    label: 'Incidents (30d)',
                    data: @json($services->map(fn($s) => $s->incidents()->where('created_at', '>=', now()->subDays(30))->count())),
                    backgroundColor: 'rgba(239, 68, 68, 0.7)',
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>
@endpush
@endsection
