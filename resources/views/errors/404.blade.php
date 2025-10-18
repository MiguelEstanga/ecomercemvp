@extends('layouts.app') {{-- Reemplazar con su layout base si lo usa --}}

@section('title', 'Página No Encontrada')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="text-center p-8 bg-white shadow-lg rounded-lg">
        <h1 class="text-9xl font-extrabold text-red-600 tracking-wider">404</h1>
        <div class="bg-red-600 text-white px-4 py-1 inline-block text-sm uppercase rounded-full mt-4">
            PÁGINA NO ENCONTRADA
        </div>
        <p class="text-gray-500 text-xl mt-6">
            Lo sentimos, no pudimos encontrar la página que está buscando.
        </p>
        <a href="{{ url('/') }}" class="mt-8 inline-block px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition duration-300">
            Ir a la Página de Inicio
        </a>
    </div>
</div>
@endsection