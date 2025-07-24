@extends('layouts.main')

@section('content')
<div class="relative flex flex-col items-center justify-center min-h-[60vh] bg-black overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-black/80 to-black/95 pointer-events-none"></div>
    <div class="w-full max-w-xl mx-auto relative z-10">
        <div id="credits-scroll" class="text-center text-white text-lg leading-relaxed meme-credits" style="white-space: pre-line; font-family: 'Figtree', sans-serif;">
            <div class="mb-8 text-4xl font-extrabold tracking-widest animate-rainbow">UpLink</div>
            <div class="mb-4 text-xl animate-bounce">A Project by</div>
            <div class="mb-12 text-2xl font-semibold animate-pulse">Sprawdzany</div>
            <div class="mb-4">Director of Vibes</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Button Presser</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">404 Handler</div>
            <div class="mb-8 text-xl font-semibold animate-wiggle">Sprawdzany</div>
            <div class="mb-4">Lead Coffee Consumer</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Quantum Bug Creator</div>
            <div class="mb-8 text-xl font-semibold animate-bounce">Sprawdzany</div>
            <div class="mb-4">Chief Snack Officer</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Tab Closer</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Pixel Arranger</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Stack Overflow Navigator</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Rubber Duck Listener</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Dark Mode Advocate</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Commit Message Poet</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Merge Conflict Resolver</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Hotfix Hero</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Feature Creep Consultant</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Infinite Loop Specialist</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Debugger Whisperer</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Semicolon Enforcer</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Variable Namer</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">API Key Hider</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Unit Test Dreamer</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">CSS Sorcerer</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Console Log Enthusiast</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Dependency Updater</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Readme Writer</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Release Note Composer</div>
            <div class="mb-8 text-xl font-semibold">Sprawdzany</div>
            <div class="mb-4">Special Thanks</div>
            <div class="mb-8 text-xl font-semibold animate-pulse">Sprawdzany</div>
            <div class="mt-16 text-lg animate-rainbow">All roles by Sprawdzany</div>
            <div class="mt-8 text-2xl animate-spin-slow"></div>
        </div>
        <div id="uplink-link" class="hidden fixed inset-0 flex flex-col items-center justify-center bg-black z-20">
            <a href="https://github.com/olususus/UpLink" target="_blank" rel="noopener" class="text-6xl font-extrabold tracking-widest text-blue-600 hover:text-blue-400 transition mt-0 mb-4" style="text-shadow: 0 4px 32px #000b; letter-spacing: 0.1em;">
                UpLink
            </a>
            <span class="text-lg text-gray-300">github.com/olususus/UpLink</span>
        </div>
        </div>
    </div>
    <style>
        #credits-scroll {
            position: relative;
            top: 60vh;
            animation: credits-move 18s linear forwards;
        }
        @keyframes credits-move {
            0% { top: 60vh; }
            100% { top: -600vh; }
        }
        .animate-rainbow {
            background: linear-gradient(90deg, #ff0080, #7928ca, #007cf0, #00dfd8, #ff0080);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: rainbow 6s ease-in-out infinite;
        }
        @keyframes rainbow {
            0%,100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .animate-spin-slow {
            animation: spin 6s linear infinite;
        }
        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
        .animate-wiggle {
            animation: wiggle 1.2s infinite;
        }
        @keyframes wiggle {
            0%, 100% { transform: rotate(-3deg); }
            50% { transform: rotate(3deg); }
        }
    </style>
    <script>
        // Show the UpLink logo after the credits finish scrolling, no forced scroll
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('credits-scroll').style.display = 'none';
                document.getElementById('uplink-link').classList.remove('hidden');
            }, 18000); // match animation duration
        });
    </script>
</div>
@endsection
