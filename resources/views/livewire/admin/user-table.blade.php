<div class="space-y-6">
    {{-- Mensajes Flash --}}
    @if (session()->has('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-stat-card title="Total Usuarios" :value="$stats['total']" icon="users" color="blue" />

        <x-stat-card title="Activos" :value="$stats['active']" icon="check" color="green" />

        <x-stat-card title="Inactivos" :value="$stats['inactive']" icon="clock" color="red" />

        <x-stat-card title="Nuevos (Este Mes)" :value="$stats['new_this_month']" icon="chart" color="indigo" />
    </div>

    {{-- Tabla --}}
    <x-data-table :headers="[
        ['label' => 'ID', 'field' => 'id', 'sortable' => true],
        ['label' => 'Usuario', 'field' => 'name', 'sortable' => true],
        ['label' => 'Email', 'field' => 'email', 'sortable' => true],
        ['label' => 'Teléfono', 'field' => 'phone', 'sortable' => false],
        ['label' => 'Rol', 'field' => 'role', 'sortable' => false],
        ['label' => 'Órdenes', 'field' => 'orders', 'sortable' => false],
        ['label' => 'Estado', 'field' => 'active', 'sortable' => false],
        ['label' => 'Registro', 'field' => 'created_at', 'sortable' => true],
        ['label' => 'Acciones', 'field' => 'actions', 'sortable' => false],
    ]" :rows="$users" empty-message="No se encontraron usuarios">
        {{-- Header --}}
        <x-slot name="header">
            <h2 class="text-xl font-bold text-white">Gestión de Usuarios</h2>
        </x-slot>

        {{-- Filtros --}}
        <x-slot name="filters">
            <div class="space-y-4">
                {{-- Primera fila de filtros --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Búsqueda --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Buscar Usuario
                            </span>
                        </label>
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="Nombre, email, teléfono, DNI..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm" />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Filtro por Estado --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select wire:model.live="statusFilter"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm bg-white">
                            <option value="">Todos</option>
                            <option value="1">✅ Activos</option>
                            <option value="0">❌ Inactivos</option>
                        </select>
                    </div>

                    {{-- Items por página --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Por Página</label>
                        <select wire:model.live="perPage"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm bg-white">
                            <option value="5">5 registros</option>
                            <option value="10">10 registros</option>
                            <option value="20">20 registros</option>
                            <option value="50">50 registros</option>
                        </select>
                    </div>
                </div>

                {{-- Segunda fila de filtros --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Filtro por Rol --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                        <select wire:model.live="roleFilter"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm bg-white">
                            <option value="">Todos los roles</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Fecha Desde --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Desde</label>
                        <input wire:model.live="dateFrom" type="date"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm" />
                    </div>

                    {{-- Fecha Hasta --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hasta</label>
                        <input wire:model.live="dateTo" type="date"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm" />
                    </div>

                    {{-- Botón Limpiar --}}
                    <div class="flex items-end">
                        <button wire:click="clearFilters"
                            class="w-full px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200 font-medium shadow-sm hover:shadow-md flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </x-slot>

        {{-- Contenido de cada fila --}}
        @foreach ($users as $user)
            <tr class="hover:bg-indigo-50 transition-colors duration-150">
                {{-- ID --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    #{{ $user->id }}
                </td>

                {{-- Usuario --}}
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <img src="{{ $user->getAvatarUrlAttribute() }}" alt="{{ $user->name }}"
                                class="h-10 w-10 rounded-full object-cover" />
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            @if ($user->profile && $user->profile->dni)
                                <div class="text-xs text-gray-500">DNI: {{ $user->profile->dni }}</div>
                            @endif
                        </div>
                    </div>
                </td>

                {{-- Email --}}
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                    @if ($user->email_verified_at)
                        <span class="text-xs text-green-600">✓ Verificado</span>
                    @else
                        <span class="text-xs text-gray-500">Sin verificar</span>
                    @endif
                </td>

                {{-- Teléfono --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $user->profile->phone ?? 'N/A' }}
                </td>

                {{-- Rol --}}
                <td class="px-6 py-4 whitespace-nowrap">
                    @if ($user->roles->count() > 0)
                        @foreach ($user->roles as $role)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                            Usuario
                        </span>
                    @endif
                </td>

                {{-- Órdenes --}}
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    @if ($user->orders->count() > 0)
                        <button wire:click="viewUserOrders({{ $user->id }})"
                            class="text-indigo-600 hover:text-indigo-900 font-semibold">
                            {{ $user->orders->count() }}
                        </button>
                    @else
                        <span class="text-gray-400">0</span>
                    @endif
                </td>

                {{-- Estado --}}
                <td class="px-6 py-4 whitespace-nowrap">
                    @if ($user->active)
                        <span
                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            ✅ Activo
                        </span>
                    @else
                        <span
                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            ❌ Inactivo
                        </span>
                    @endif
                </td>

                {{-- Fecha de Registro --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div>{{ $user->created_at->format('d/m/Y') }}</div>
                    <div class="text-xs text-gray-400">{{ $user->created_at->format('H:i') }}</div>
                </td>

                {{-- Acciones --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <button wire:click="viewUser({{ $user->id }})"
                        class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-colors duration-200"
                        title="Ver perfil">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Ver
                    </button>

                    <button wire:click="toggleUserStatus({{ $user->id }})"
                        wire:confirm="¿Estás seguro de {{ $user->active ? 'desactivar' : 'activar' }} este usuario?"
                        class="inline-flex items-center px-3 py-1.5 {{ $user->active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg transition-colors duration-200"
                        title="{{ $user->active ? 'Desactivar' : 'Activar' }}">
                        @if ($user->active)
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            Desactivar
                        @else
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Activar
                        @endif
                    </button>

                    @if ($user->id !== 1 && $user->id !== auth()->id())
                        <button wire:click="deleteUser({{ $user->id }})"
                            wire:confirm="¿Estás seguro de eliminar permanentemente este usuario?"
                            class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200"
                            title="Eliminar">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach

        {{-- Paginación --}}
        <x-slot name="pagination">
            {{ $users->links() }}
        </x-slot>
    </x-data-table>

    {{-- Modales --}}
    <livewire:admin.user-details />
    <livewire:admin.user-orders />
</div>
