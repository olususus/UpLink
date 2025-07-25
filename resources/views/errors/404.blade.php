@extends('layouts.main')

@section('title', '404 Not Found')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] py-8">
    <h1 class="text-5xl font-extrabold text-red-600 mb-4">404</h1>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">Page Not Found</h2>
    <p class="text-gray-600 dark:text-gray-400 mb-6">Sorry, the page you are looking for could not be found.</p>
    <a href="/" class="inline-block px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Go Home</a>
</div>
@endsection
