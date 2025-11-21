<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    <link rel="icon" type="image/png" href="/icon.ico">

    {{-- La directiva @vite es esencial para cargar los estilos compilados de Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-white min-h-screen flex items-center justify-center antialiased">
    @if (session()->has('success'))
        <x-alert-component type="message" message="{{ session('message') }}" />
    @endif
    {{-- Tarjeta de Login (bg-gray-800 para contraste) --}}
    <div class="max-w-md w-full p-8 space-y-8 bg-gray-800 rounded-xl shadow-2xl z-10 bg-white">

        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-black">
                Crear Cuenta
            </h2>
            <p class="mt-2 text-sm text-black">
                Usa tu cuenta para continuar
            </p>
        </div>


        <form class="mt-8 space-y-6" action="{{ route(name: 'register.post') }}" method="POST">
            @csrf

            <x-form-input name="name" label="Nombre" placeholder="Nombre" required />
            <x-form-input name="email" label="Correo Electrónico" placeholder="correo@ejemplo.com" required />
            <x-form-input name="password" label="Contraseña" placeholder="******" required />
            <x-form-input name="password_confirmation" label="Confirmar Contraseña" placeholder="******" required />
            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-900">

                    Iniciar Sesión
                </button>
            </div>
        </form>
    </div>
</body>

</html>
