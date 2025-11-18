<div>
    @if($showModal && $user)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                {{-- Modal --}}
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <img 
                                    src="{{ $user->avatar_url }}" 
                                    alt="{{ $user->name }}"
                                    class="h-12 w-12 rounded-full border-2 border-white"
                                />
                                <div class="ml-4">
                                    <h3 class="text-xl font-bold text-white">{{ $user->name }}</h3>
                                    <p class="text-indigo-100 text-sm">{{ $user->email }}</p>
                                </div>
                            </div>
                            <button wire:click="closeModal" class="text-white hover:text-gray-200">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="max-h-[calc(100vh-200px)] overflow-y-auto p-6">
                        
                        {{-- Mensajes Flash --}}
                        @if (session()->has('success'))
                            <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        @endif
                        
                        @if (session()->has('error'))
                            <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        @endif

                        {{-- Estadísticas Rápidas --}}
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Órdenes</p>
                                        <p class="text-2xl font-bold text-gray-900">{{ $user->orders->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Total Gastado</p>
                                        <p class="text-2xl font-bold text-gray-900">${{ number_format($user->orders->sum('total_amount'), 2) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Estado</p>
                                        <p class="text-lg font-bold {{ $user->active ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $user->active ? 'Activo' : 'Inactivo' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-indigo-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Miembro desde</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $user->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Información del Usuario --}}
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold text-gray-900">Información Personal</h2>
                                <button 
                                    wire:click="toggleEdit"
                                    class="px-4 py-2 text-sm font-medium {{ $editing ? 'bg-gray-200 text-gray-700' : 'bg-indigo-600 text-white' }} rounded-lg hover:opacity-80 transition-colors"
                                >
                                    {{ $editing ? 'Cancelar' : 'Editar' }}
                                </button>
                            </div>

                            @if($editing)
                                {{-- Formulario de Edición --}}
                                <form wire:submit.prevent="updateUser" class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {{-- Nombre --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                                            <input 
                                                type="text" 
                                                wire:model="name"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            />
                                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                            <input 
                                                type="email" 
                                                wire:model="email"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            />
                                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Teléfono --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                            <input 
                                                type="text" 
                                                wire:model="phone"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            />
                                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- DNI --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">DNI/Cédula</label>
                                            <input 
                                                type="text" 
                                                wire:model="dni"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            />
                                            @error('dni') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- País --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">País</label>
                                            <input 
                                                type="text" 
                                                wire:model="country"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            />
                                            @error('country') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Ciudad --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad</label>
                                            <input 
                                                type="text" 
                                                wire:model="city"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            />
                                            @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    {{-- Dirección --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                                        <textarea 
                                            wire:model="address"
                                            rows="2"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        ></textarea>
                                        @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Avatar --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Avatar</label>
                                        <input 
                                            type="file" 
                                            wire:model="avatar"
                                            accept="image/*"
                                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                        />
                                        @error('avatar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        
                                        @if ($avatar)
                                            <div class="mt-2">
                                                <img src="{{ $avatar->temporaryUrl() }}" class="w-20 h-20 rounded-full object-cover">
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Botón Guardar --}}
                                    <div class="flex justify-end">
                                        <button 
                                            type="submit"
                                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                                            wire:loading.attr="disabled"
                                        >
                                            <span wire:loading.remove>Guardar Cambios</span>
                                            <span wire:loading>Guardando...</span>
                                        </button>
                                    </div>
                                </form>
                            @else
                                {{-- Vista de Solo Lectura --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Nombre Completo</p>
                                        <p class="text-gray-900 mt-1">{{ $user->name }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Email</p>
                                        <p class="text-gray-900 mt-1">{{ $user->email }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Teléfono</p>
                                        <p class="text-gray-900 mt-1">{{ $user->profile->phone ?? 'No especificado' }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-gray-500">DNI/Cédula</p>
                                        <p class="text-gray-900 mt-1">{{ $user->profile->dni ?? 'No especificado' }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-gray-500">País</p>
                                        <p class="text-gray-900 mt-1">{{ $user->profile->country ?? 'No especificado' }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Ciudad</p>
                                        <p class="text-gray-900 mt-1">{{ $user->profile->city ?? 'No especificado' }}</p>
                                    </div>

                                    <div class="md:col-span-2">
                                        <p class="text-sm font-medium text-gray-500">Dirección</p>
                                        <p class="text-gray-900 mt-1">{{ $user->profile->address ?? 'No especificada' }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Roles</p>
                                        <div class="mt-1 flex flex-wrap gap-2">
                                            @forelse($user->roles as $role)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    {{ ucfirst($role->name) }}
                                                </span>
                                            @empty
                                                <span class="text-gray-500 text-sm">Sin roles asignados</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Verificación de Email</p>
                                        <p class="text-gray-900 mt-1">
                                            @if($user->email_verified_at)
                                                <span class="text-green-600">✓ Verificado el {{ $user->email_verified_at->format('d/m/Y') }}</span>
                                            @else
                                                <span class="text-red-600">✗ Sin verificar</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Últimas Órdenes --}}
                        @if($user->orders->count() > 0)
                        <div class="bg-white rounded-lg border border-gray-200 p-6 mt-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold text-gray-900">Últimas Órdenes</h2>
                                <button 
                                    wire:click="$dispatch('openUserOrdersModal', { userId: {{ $user->id }} })"
                                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium"
                                >
                                    Ver todas →
                                </button>
                            </div>

                            <div class="space-y-3">
                                @foreach($user->orders->take(5) as $order)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $order->order_number }}</p>
                                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <div class="text-right mr-4">
                                            <p class="text-sm font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                                        </div>
                                        <div>
                                            @switch($order->status)
                                                @case('pending')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                                    @break
                                                @case('completed')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completado</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Cancelado</span>
                                                    @break
                                                @default
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ ucfirst($order->status) }}</span>
                                            @endswitch
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>