@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    <h1 class="text-3xl font-bold text-gray-900 mb-6">Dashboard Administrativo</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-indigo-500">
            <p class="text-sm font-medium text-gray-500">Órdenes Totales</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">1,250</p>
            <p class="text-xs text-green-500 mt-2">+10% esta semana</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-500">Ventas Totales</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">$45,800</p>
            <p class="text-xs text-red-500 mt-2">-2% esta semana</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-500">
            <p class="text-sm font-medium text-gray-500">Productos Activos</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">325</p>
            <p class="text-xs text-green-500 mt-2">Nuevo producto añadido</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-red-500">
            <p class="text-sm font-medium text-gray-500">Usuarios Registrados</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">5,120</p>
            <p class="text-xs text-green-500 mt-2">+50 nuevos</p>
        </div>
        
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Órdenes Realizadas por Día (Gráfica)</h2>
        <div class="h-64 flex items-center justify-center text-gray-400">
            [Aquí iría tu gráfica, posiblemente con Chart.js o similar]
        </div>
    </div>
    
@endsection