

@extends('layouts.auth-minimal')
@section('title', __('Login'))

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen">
    @if(site_logo_url())
        <img src="{{ site_logo_url() }}" alt="Site Logo" class="max-h-24 h-24 w-auto object-contain mb-6 animate-fade-in" style="animation-delay:0.1s">
    @endif
    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8 animate-slide-up animate-fade-in" style="animation-delay:0.2s">
        <h1 class="text-3xl font-extrabold text-center text-gray-900 dark:text-gray-100 mb-8 tracking-tight animate-fade-in" style="animation-delay:0.3s">{{ __('Sign in to your account') }}</h1>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="animate-fade-in" style="animation-delay:0.4s">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="animate-fade-in" style="animation-delay:0.5s">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between animate-fade-in" style="animation-delay:0.6s">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">{{ __('Remember me') }}</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:underline dark:text-blue-400" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="animate-fade-in" style="animation-delay:0.7s">
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-md text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-1 hover:scale-105">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
@keyframes fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}
.animate-fade-in {
  animation: fade-in 0.7s both;
}
@keyframes slide-up {
  from { transform: translateY(40px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
.animate-slide-up {
  animation: slide-up 0.7s both;
}
</style>
@endpush
