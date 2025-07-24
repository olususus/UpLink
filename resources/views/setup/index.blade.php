@extends('layouts.guest')

@section('content')
<div class="max-w-lg mx-auto mt-12 bg-white dark:bg-gray-800 p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Initial Setup</h1>
    <form method="POST" action="{{ route('setup.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Site Name</label>
            <input type="text" name="app_name" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Admin Email</label>
            <input type="email" name="admin_email" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Admin Password</label>
            <input type="password" name="admin_password" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-semibold">Complete Setup</button>
    </form>
</div>
@endsection
