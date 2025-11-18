@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')


    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">📦 Productos</h1>
        <button onclick="Livewire.dispatch('open-product-modal')" class="... (clases del botón)">
            Crear Producto
        </button>
    </div>
    @livewire('admin.product-table')
    @livewire('admin.product-form')

@endsection
