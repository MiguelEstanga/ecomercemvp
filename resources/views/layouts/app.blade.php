<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/icon.ico">
    <title>@yield('title', config('seo.site_name'))</title>

    <meta property="og:title" content="@yield('title', config('seo.site_name'))">
    <meta property="og:description" content="@yield('description', config('seo.site_description'))">
    <meta property="og:image" content="{{ asset(config('seo.og_image')) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', config('seo.site_name'))">
    <meta name="twitter:description" content="@yield('description', config('seo.site_description'))">
    <meta name="twitter:image" content="{{ asset(config('seo.og_image')) }}">

    <link rel="canonical" href="{{ url()->current() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* CSS simple para asegurar que el footer se quede abajo */
        .flex-wrapper {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            justify-content: space-between;
        }
    </style>
</head>

<body class="color_fondo">

    <div class="flex-wrapper">
        <header class="bg-pink-600 sticky top-0 z-50 shadow-xl">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
                <div class="flex justify-between items-center h-16">

                    {{-- Logo y Nombre --}}
                    <a href="{{ route('home') }}" class="flex gap-2">
                        <img src="/icon.ico" alt="Logo" class="h-10 w-auto object-contain rounded-lg">
                        <div class="flex-shrink-0 flex items-center text-white">
                            <span class="text-2xl font-bold text-indigo">Cytotecfemvenezuela</span>
                        </div>
                    </a>

                    {{-- Buscador (Centrado y funcionalmente simple) --}}
                    <div class="hidden sm:block">
                        <input id="search-input" type="search" placeholder="Buscar..."
                            class="py-2 px-4 w-96 bg-white border-gray-600 rounded-sm focus:outline-none  placeholder-black text-sky">
                    </div>

                    {{-- Menú de Usuario --}}
                    <div class="flex items-center space-x-2">
                        @auth {{-- Solo si el usuario ha iniciado sesión --}}
                            <a href="{{ route('home') }}"
                                class="onclick text-white hover:bg-black hover:text-white px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                Inicio
                            </a>
                            <a href="{{ route('profile.index') }}"
                                class="onclick text-white hover:bg-black hover:text-white px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                Perfil
                            </a>

                            @if (Auth::user()->roles->first()->name == 'administrador')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="onclick text-white hover:bg-black hover:text-white px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                    Administración
                                </a>
                            @endif


                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="onclick text-white hover:bg-black hover:text-white px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                    Salir
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-sm font-medium text-indigo-400 hover:text-indigo-300">
                                Iniciar Sesión
                            </a>
                        @endauth
                    </div>
                </div>
            </nav>
        </header>


        <main class="flex-grow  ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>


        <footer class="bg-white mt-10">
            <div
                class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500   border-gray-700">
                <p>&copy; {{ date('Y') }} Mi Aplicación. Todos los derechos reservados.</p>
                {{-- <div class="mt-2 space-x-4">
                    <a href="#" class="hover:text-gray-300 transition duration-150">Privacidad</a>
                    <a href="#" class="hover:text-gray-300 transition duration-150">Términos</a>
                    <a href="#" class="hover:text-gray-300 transition duration-150">Contacto</a>
                </div> --}}
            </div>
        </footer>

    </div>
    @stack('scripts')
    {{-- Livewire Scripts --}}
    @livewireScripts

    {{-- Scripts adicionales --}}
    @stack('scripts')
</body>


</html>
