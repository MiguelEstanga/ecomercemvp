@extends('layouts.app')
@section('title', 'Mi Perfil')
@section('content')


    @if (session()->has('success'))
        <div class="mt-4">
            <x-alert-component type="success" message="{{ session('success') }}" />
        </div>
    @endif
    @if ($profile == null)
        <div class="mt-4">
            <x-alert-component type="warning"
                message="Para que podamos atendener tu pedido, Completa tu perfil con datos realess" />
        </div>
    @endif


    @if ($profile == null)
        <div class="mt-4">
            <x-modal :show="true" name="completa_perfil" title="Completa tu perfil" size="md">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <x-select-input name="country" label="País" placeholder="Selecciona un país" :options="[
                        ['id' => 1, 'name' => 'España'],
                        ['id' => 2, 'name' => 'Italia'],
                        ['id' => 3, 'name' => 'Alemania'],
                    ]" />
                    <x-select-input name="city" label="Ciudad" placeholder="Selecciona una ciudad" :options="[
                        ['id' => 1, 'name' => 'Madrid'],
                        ['id' => 2, 'name' => 'Barcelona'],
                        ['id' => 3, 'name' => 'Valencia'],
                    ]" />
                    <x-text-input name="address" label="Direccion" placeholder="Avenida tal" />
                    <x-text-input name="dni" label="Cedula" placeholder="Cedula" />
                    <x-file-upload name="avatar" label="Imagen de perfil" accept="image/*" maxSize="5MB"
                        allowedFormats="PNG, JPG" />

                    <button class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Confirmar
                    </button>
                </form>
                <x-slot:footer>
                    <button onclick="closeModal('editarPerfil')"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Cancelar
                    </button>

                </x-slot:footer>

            </x-modal>
        </div>
    @endif
    <div class="max-w-7xl mx-auto px-4 sm:px-6 ">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">

            <!-- Información del Usuario -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="   rounded-full wi-24 he-24 ">
                        @if ($profile != null)
                            @php
                                // Quitar 'public/' de la ruta
                                $cleanPath = str_replace('public/', '', $profile->avatar);
                            @endphp
                            <img width="75" height="50" src="{{ asset('storage/' . $cleanPath) }}"
                                alt="{{ $profile->user->name }}" />
                        @endif

                    </div>


                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900">{{ auth()->user()->email }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Miembro desde</label>
                            <p class="text-gray-900">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                        </div>

                        <div class="pt-4 ">
                            <button onclick="openModal('editarPerfil')" class="button-primary">
                                Editar Usuario
                            </button>
                        </div>
                    </div>
                    @if ($profile != null)
                        <div class="mt-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Numero de Telefono</label>
                                <p class="text-gray-900">{{ $profile->phone ?? 'Sin datos' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">País</label>
                                <p class="text-gray-900">{{ $profile->country ?? 'Sin datos' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Ciudad</label>
                                <p class="text-gray-900">{{ $profile->city ?? 'Sin datos' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Dirección</label>
                                <p class="text-gray-900">{{ $profile->address ?? 'Sin datos' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">DNI</label>
                                <p class="text-gray-900">{{ $profile->dni ?? 'Sin datos' }}</p>
                            </div>
                            <div class="pt-4 ">
                                <button onclick="openModal('editarPerfil2')" class="button-primary">
                                    Editar Perfil
                                </button>
                            </div>
                        </div>
                    @endif
                </div>


            </div>

            <!-- Órdenes del Usuario -->
            <div class="lg:col-span-2">
                <div class=" rounded-lg  ">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Mis Órdenes</h2>
                    </div>

                    <div class="p-6">
                        @if ($orders->isEmpty())
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
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
                            <div class="space-y-4   ">
                                @foreach ($orders as $order)
                                    <div
                                        class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                        <div class="flex justify-between items-start mb-3 px-4 py-4 divider">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            <span
                                                class="px-3 py-1 text-xs font-semibold rounded-full
                                                @if ($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                                @elseif($order->status === 'completed') bg-green-100 text-green-800
                                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>

                                        <div class=" px-4 py-4">
                                            @php
                                                $item = $order->items->first();
                                            @endphp
                                            <div class="flex items-center justify-between py-2">
                                                <div class="flex items-center space-x-3">
                                                    @php
                                                        $cleanPath = str_replace('public/', '', $item->product->product_imagens[0]->path);
                                                    @endphp
                                                    <img width="74" height="74" style="border-radius: 5px"
                                                        src="/storage/{{ $cleanPath }}"
                                                        class="bg-black">

                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $item->product->name }}</p>
                                                        <p class="text-xs text-gray-500">Cantidad:
                                                            {{ $item->quantity }}</p>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="text-generico font-semibold text-black">
                                                        Total de la Compra
                                                    </p>
                                                    <p class="text-sm font-semibold text-green">
                                                        ${{ number_format($order->total_amount, 2) }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <a href="{{ route('order.show', $order->id) }}"
                                                        class="button-primary">
                                                        Ver Detalles
                                                    </a>
                                                </div>
                                            </div>

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
    <div class="mt-4">
        <x-modal name="editarPerfil2" title="Completa tu perfil" size="md">
            @php
                // Quitar 'public/' de la ruta
                $cleanPath = str_replace('public/', '', $profile->avatar ?? "");
                $path = asset('storage/' . $cleanPath);
            @endphp
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <x-select-input name="country" label="País" placeholder="Selecciona un país" :options="[
                    ['id' => 1, 'name' => 'España'],
                    ['id' => 2, 'name' => 'Italia'],
                    ['id' => 3, 'name' => 'Alemania'],
                ]" />
                <x-select-input name="city" label="Ciudad" placeholder="Selecciona una ciudad" :options="[
                    ['id' => 1, 'name' => 'Madrid'],
                    ['id' => 2, 'name' => 'Barcelona'],
                    ['id' => 3, 'name' => 'Valencia'],
                ]" />
                <x-text-input name="address" label="Direccion" placeholder="Avenida tal"
                    value="{{ $profile->address ?? 'Sin datos' }}" />
                <x-text-input name="dni" label="Cedula" placeholder="Cedula"
                    value="{{ $profile->dni ?? 'Sin datos' }}" />
                <x-file-upload :defaultImage="$path" name="avatar" label="Imagen de perfil" accept="image/*" maxSize="5MB"
                    allowedFormats="PNG, JPG" />

                <div class="flex justify-center items-center mt-4">
                    <button type="submit" class="button-primary">
                        Confirmar
                    </button>
                </div>

            </form>


        </x-modal>
    </div>
    <x-modal name="editarPerfil" title="Editar Información del usuario" size="md">
        <form action="{{ route('user.update', auth()->user()->id) }}" method="POST">
            <div>

                @csrf
                <x-text-input name="name" label="Nombre" placeholder="Nombre" value="{{ auth()->user()->name }}" />
                <x-text-input name="email" label="Email" placeholder="Email" value="{{ auth()->user()->email }}" />

                <button class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Confirmar
                </button>
            </div>
        </form>
        <x-slot:footer>
            <button onclick="closeModal('editarPerfil')"
                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                Cancelar
            </button>

        </x-slot:footer>

    </x-modal>
@endsection
