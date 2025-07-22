@extends('admin.layout')

@section('title', 'Create Incident')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
            <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Create New Incident</h1>
            <a href="{{ route('admin.incidents.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Incidents
            </a>
        </div>

        <form method="POST" action="{{ route('admin.incidents.store') }}">
            @csrf

            <!-- Service Selection -->
            <div class="mb-4">
                <label for="service_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service</label>
                <select name="service_id" id="service_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Select a service</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
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
                <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Severity -->
            <div class="mb-4">
                <label for="severity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Severity</label>
                <select name="severity" id="severity" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Select severity</option>
                    <option value="minor" {{ old('severity') == 'minor' ? 'selected' : '' }}>Minor</option>
                    <option value="major" {{ old('severity') == 'major' ? 'selected' : '' }}>Major</option>
                    <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
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
                    <option value="investigating" {{ old('status') == 'investigating' ? 'selected' : '' }}>Investigating</option>
                    <option value="identified" {{ old('status') == 'identified' ? 'selected' : '' }}>Identified</option>
                    <option value="monitoring" {{ old('status') == 'monitoring' ? 'selected' : '' }}>Monitoring</option>
                    <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Started At -->
            <div class="mb-6">
                <label for="started_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Started At</label>
                <input type="datetime-local" name="started_at" id="started_at" value="{{ old('started_at', now()->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('started_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create Incident
                </button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
