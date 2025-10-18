<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Elegante | Tema Oscuro</title>

    {{-- La directiva @vite es esencial para cargar los estilos compilados de Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-900 min-h-screen flex items-center justify-center antialiased">

    {{-- Tarjeta de Login (bg-gray-800 para contraste) --}}
    <div class="max-w-md w-full p-8 space-y-8 bg-gray-800 rounded-xl shadow-2xl z-10">

        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-100">
                Acceso a la Plataforma
            </h2>
            <p class="mt-2 text-sm text-gray-400">
                Usa tu cuenta para continuar
            </p>
        </div>


        <form class="mt-8 space-y-6" action="{{ route(name: 'login.post') }}" method="POST">
            @csrf

            {{-- Campo Email --}}
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Correo Electrónico</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="appearance-none relative block w-full px-3 py-2 border border-gray-700 placeholder-gray-500 text-gray-100 bg-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm rounded-t-md"
                        placeholder="Correo Electrónico" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Campo Contraseña --}}
            <div>
                <label for="password" class="sr-only">Contraseña</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required
                    class="appearance-none relative block w-full px-3 py-2 border border-gray-700 placeholder-gray-500 text-gray-100 bg-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm rounded-b-md"
                    placeholder="Contraseña">
                @error('password')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Recordarme y Enlace de Olvido --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-600 rounded bg-gray-700">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-400">
                        Recordarme
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-indigo-400 hover:text-indigo-300">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </div>

            {{-- Botón de Login (Color de acento: Índigo) --}}
            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-900">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        {{-- Icono de Candado --}}
                        <svg class="h-5 w-5 text-indigo-300 group-hover:text-indigo-200"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    Iniciar Sesión
                </button>
            </div>
        </form>
    </div>
</body>

</html>
