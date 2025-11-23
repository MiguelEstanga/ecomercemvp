<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel de Administración')</title>
    <link rel="icon" type="image/png" href="/icon.ico">

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
    <livewire:components.loader wire:key="loader-global" />

    {{-- Livewire Scripts --}}
    @livewireScripts

    {{-- Scripts adicionales --}}
    @stack('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            const loader = document.getElementById('loader');
             
            
            Livewire.on('startLoading', (data) => {
                 
                console.log(loader);
                console.log('corgando');
                loader.classList.remove('hidden'); 
            });

            Livewire.on('stopLoading', () => {
                console.log('finalizando');
                 console.log(loader);
               // loader.classList.add('hidden');
                
            });
        });
    </script>
</body>

</html>
