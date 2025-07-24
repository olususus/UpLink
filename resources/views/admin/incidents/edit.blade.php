@extends('admin.layout')

@section('title', 'Edit Incident')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
            <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Edit Incident: {{ $incident->title }}</h1>
            <div class="space-x-2">
                @if(!$incident->is_resolved)
                    <form method="POST" action="{{ route('admin.incidents.resolve', $incident) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to resolve this incident?')">
                            Resolve Incident
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.incidents.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Incidents
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.incidents.update', $incident) }}">
            @csrf
            @method('PUT')

            <!-- Service Selection -->
            <div class="mb-4">
                <label for="service_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service</label>
                <select name="service_id" id="service_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Select a service</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ (old('service_id', $incident->service_id) == $service->id) ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
                @error('service_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Incident Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $incident->title) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('description', $incident->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Severity -->
            <div class="mb-4">
                <label for="severity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Severity</label>
                <select name="severity" id="severity" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Select severity</option>
                    <option value="minor" {{ old('severity', $incident->severity) == 'minor' ? 'selected' : '' }}>Minor</option>
                    <option value="major" {{ old('severity', $incident->severity) == 'major' ? 'selected' : '' }}>Major</option>
                    <option value="critical" {{ old('severity', $incident->severity) == 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
                @error('severity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Select status</option>
                    <option value="investigating" {{ old('status', $incident->status) == 'investigating' ? 'selected' : '' }}>Investigating</option>
                    <option value="identified" {{ old('status', $incident->status) == 'identified' ? 'selected' : '' }}>Identified</option>
                    <option value="monitoring" {{ old('status', $incident->status) == 'monitoring' ? 'selected' : '' }}>Monitoring</option>
                    <option value="resolved" {{ old('status', $incident->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Started At -->
            <div class="mb-4">
                <label for="started_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Started At</label>
                <input type="datetime-local" name="started_at" id="started_at" value="{{ old('started_at', $incident->started_at->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('started_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Resolved At (if resolved) -->
            @if($incident->is_resolved)
                <div class="mb-4">
                    <label for="resolved_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Resolved At</label>
                    <input type="datetime-local" name="resolved_at" id="resolved_at" value="{{ old('resolved_at', $incident->resolved_at?->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('resolved_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <div class="flex items-center justify-between">
                <div>
                    @if($incident->is_resolved)
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            Resolved
                        </span>
                    @else
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            Active
                        </span>
                    @endif
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Incident
                </button>
            </div>
        </form>

        <!-- Incident Timeline -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Incident Timeline</h3>
            <div class="space-y-2 text-sm text-gray-600">
                <div>
                    <strong>Created:</strong> {{ $incident->started_at->format('M j, Y H:i:s') }}
                </div>
                @if($incident->is_resolved && $incident->resolved_at)
                    <div>
                        <strong>Resolved:</strong> {{ $incident->resolved_at->format('M j, Y H:i:s') }}
                    </div>
                    <div>
                        <strong>Duration:</strong> {{ $incident->duration }}
                    </div>
                @endif
                <div>
                    <strong>Last Updated:</strong> {{ $incident->updated_at->format('M j, Y H:i:s') }}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
