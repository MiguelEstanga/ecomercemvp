@extends('layouts.app')
@section('title', 'Mi Perfil')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Mi Perfil</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información del Usuario -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center">
                            <span class="text-3xl font-bold text-indigo-600">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                    </div>
                    
                    <h2 class="text-xl font-semibold text-center mb-6">{{ auth()->user()->name }}</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900">{{ auth()->user()->email }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-500">Miembro desde</label>
                            <p class="text-gray-900">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                        </div>
                        
                        <div class="pt-4 border-t">
                            <a href=" " 
                               class="block w-full text-center bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition">
                                Editar Perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Órdenes del Usuario -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Mis Órdenes</h2>
                    </div>
                    
                    <div class="p-6">
                        @if($orders->isEmpty())
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay órdenes</h3>
                                <p class="mt-1 text-sm text-gray-500">Comienza a comprar para ver tus órdenes aquí.</p>
                                <div class="mt-6">
                                    <a href=" " 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        Explorar Productos
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($orders as $order)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h3 class="font-semibold text-gray-900">Orden #{{ $order->id }}</h3>
                                                <p class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                                @elseif($order->status === 'completed') bg-green-100 text-green-800
                                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                        
                                        <div class="border-t pt-3 mt-3">
                                            @foreach($order->items as $item)
                                                <div class="flex items-center justify-between py-2">
                                                    <div class="flex items-center space-x-3">
                                                        @if($item->product->image)
                                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                                 alt="{{ $item->product->name }}"
                                                                 class="w-12 h-12 object-cover rounded">
                                                        @endif
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                                                            <p class="text-xs text-gray-500">Cantidad: {{ $item->quantity }}</p>
                                                        </div>
                                                    </div>
                                                    <p class="text-sm font-semibold text-gray-900">
                                                        ${{ number_format($item->price * $item->quantity, 2) }}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <div class="flex justify-between items-center mt-4 pt-4 border-t">
                                            <p class="text-lg font-bold text-gray-900">
                                                Total: ${{ number_format($order->total_amount, 2) }}
                                            </p>
                                            <a href="{{ route('order.show', $order->id) }}" 
                                               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                Ver Detalles →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Paginación -->
                            <div class="mt-6">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection