<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Favicons --}}
    <link rel="icon" type="image/png" href="/icon.ico">
    <link rel="apple-touch-icon" href="/icon.ico">
    <title>@yield('title')</title>


    <link rel="canonical" href="{{ url()->current() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- SEO Básico --}}
    <title>@yield('title', config('seo.site_name'))</title>
    <meta name="description" content="@yield('description', config('seo.site_description'))">
    <meta name="keywords" content="@yield('keywords', config('seo.site_keywords'))">
    <meta name="author" content="{{ config('seo.author') }}">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Spanish">
    <meta name="revisit-after" content="7 days">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', config('seo.site_name'))">
    <meta property="og:description" content="@yield('og_description', config('seo.site_description'))">
    <meta property="og:image" content="{{ asset(config('seo.og_image')) }}">
    <meta property="og:site_name" content="{{ config('seo.site_name') }}">
    <meta property="og:locale" content="es_VE">

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('twitter_title', config('seo.site_name'))">
    <meta name="twitter:description" content="@yield('twitter_description', config('seo.site_description'))">
    <meta name="twitter:image" content="{{ asset(config('seo.og_image')) }}">
    <meta name="twitter:site" content="{{ config('seo.twitter_handle') }}">
    {{-- Google Site Verification - AGREGAR CUANDO LO OBTENGAS --}}
    <meta name="google-site-verification" content="WmwhuvSo-LqgLykIqyhtp9Jp4JkurAQKbVSDlUCaQak" />
    <meta name="geo.region" content="VE">
    <meta name="geo.placename" content="Venezuela">
   
   
     
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
