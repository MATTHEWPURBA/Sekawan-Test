<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @php
            $manifestPath = public_path('build/manifest.json');
            $buildDir = public_path('build');
            // Ensure build directory exists
            if (!is_dir($buildDir)) {
                @mkdir($buildDir, 0755, true);
            }
            $hasViteBuild = file_exists($manifestPath);
        @endphp

        @if ($hasViteBuild)
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <!-- Fallback: Tailwind CSS via CDN (Production) -->
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    theme: {
                        extend: {
                            fontFamily: {
                                sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                            },
                        },
                    },
                    plugins: [],
                }
            </script>
            <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @endif
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Scripts Stack -->
        @stack('scripts')
    </body>
</html>
