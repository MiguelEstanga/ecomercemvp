<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel de Administración')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Opcional: Para una transición suave en el sidebar */
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }
    </style>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
       
</head>

<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen">

        @include('layouts.partials.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">



            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 p-6">
                @yield('content')
            </main>

        </div>
    </div>
    <livewire:components.loader />
    {{-- Livewire Scripts --}}
    @livewireScripts

    {{-- Scripts adicionales --}}
    @stack('scripts')
</body>

</html>
