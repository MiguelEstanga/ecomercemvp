@extends('layouts.app') 

@section('title', 'Solicitud Incorrecta')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="text-center p-8 bg-white shadow-lg rounded-lg">
        <h1 class="text-9xl font-extrabold text-yellow-600 tracking-wider">400</h1>
        <div class="bg-yellow-600 text-white px-4 py-1 inline-block text-sm uppercase rounded-full mt-4">
            SOLICITUD INCORRECTA
        </div>
        <p class="text-gray-500 text-xl mt-6">
            Su navegador (o cliente) ha enviado una solicitud que el servidor no pudo entender.
        </p>
        <a href="{{ url('/') }}" class="mt-8 inline-block px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition duration-300">
            Volver
        </a>
    </div>
</div>
@endsection