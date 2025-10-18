@extends('layouts.app') 

@section('title', 'Error del Servidor')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="text-center p-8 bg-white shadow-lg rounded-lg">
        <h1 class="text-9xl font-extrabold text-gray-800 tracking-wider">500</h1>
        <div class="bg-gray-800 text-white px-4 py-1 inline-block text-sm uppercase rounded-full mt-4">
            ERROR INTERNO DEL SERVIDOR
        </div>
        <p class="text-gray-500 text-xl mt-6">
            Algo salió muy mal. Hemos sido notificados y estamos trabajando para solucionarlo.
        </p>
        <a href="{{ url('/') }}" class="mt-8 inline-block px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition duration-300">
            Ir a la Página de Inicio
        </a>
    </div>
</div>
@endsection