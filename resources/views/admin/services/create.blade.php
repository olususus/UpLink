@extends('admin.layout')

@section('title', 'Create Service')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <h1 class="text-2xl font-semibold mb-6">Create Service (Simple)</h1>
            <form method="POST" action="{{ route('admin.services.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL to Monitor</label>
                    <input type="url" name="url" id="url" value="{{ old('url') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <input type="text" name="description" id="description" value="{{ old('description') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="check_interval" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check Interval</label>
                    <select name="check_interval" id="check_interval" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="60">Every minute</option>
                        <option value="300" selected>Every 5 minutes</option>
                        <option value="600">Every 10 minutes</option>
                        <option value="1800">Every 30 minutes</option>
                        <option value="3600">Every hour</option>
                    </select>
                </div>
                <input type="hidden" name="type" value="automatic">
                <input type="hidden" name="is_active" value="1">
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.services.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded transition-colors duration-300">Cancel</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition-colors duration-300">Create Service</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
