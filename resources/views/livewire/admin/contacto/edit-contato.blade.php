<div>
    @if($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeEditModal"></div>

                {{-- Modal --}}
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Editar Contacto Bancario</h3>
                                    <p class="text-blue-100 text-sm">ID: #{{ $contatoId }}</p>
                                </div>
                            </div>
                            <button wire:click="closeEditModal" class="text-white hover:text-gray-200">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Content --}}
                    <form wire:submit.prevent="updateContato">
                        <div class="p-6 space-y-6">
                            
                            {{-- Información del Banco --}}
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    Información Bancaria
                                </h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Nombre del Banco --}}
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nombre del Banco <span class="text-red-500">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            wire:model="nombre_banco"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre_banco') border-red-500 @enderror"
                                            placeholder="Ej: Banco de Venezuela"
                                        />
                                        @error('nombre_banco')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Código del Banco --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Código del Banco
                                        </label>
                                        <input 
                                            type="text" 
                                            wire:model="codigo_banco"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('codigo_banco') border-red-500 @enderror"
                                            placeholder="Ej: 0102"
                                            maxlength="10"
                                        />
                                        @error('codigo_banco')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Número de Cuenta --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Número de Cuenta
                                        </label>
                                        <input 
                                            type="text" 
                                            wire:model="cuenta_banco"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cuenta_banco') border-red-500 @enderror"
                                            placeholder="Ej: 01020123456789012345"
                                            maxlength="50"
                                        />
                                        @error('cuenta_banco')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Información de Contacto --}}
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    Teléfonos de Contacto
                                </h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Teléfono de Contacto --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Teléfono de Contacto
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                            </div>
                                            <input 
                                                type="text" 
                                                wire:model="telefono_contacto"
                                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('telefono_contacto') border-red-500 @enderror"
                                                placeholder="Ej: 04121234567"
                                                maxlength="20"
                                            />
                                        </div>
                                        @error('telefono_contacto')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Teléfono del Banco --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Teléfono del Banco
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                            </div>
                                            <input 
                                                type="text" 
                                                wire:model="telefono_banco"
                                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('telefono_banco') border-red-500 @enderror"
                                                placeholder="Ej: 05001234567"
                                                maxlength="20"
                                            />
                                        </div>
                                        @error('telefono_banco')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Estado Activo --}}
                            <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <input 
                                    type="checkbox" 
                                    wire:model="is_active"
                                    id="is_active_edit"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                />
                                <label for="is_active_edit" class="ml-3 text-sm font-medium text-gray-700">
                                    Contacto activo y visible
                                </label>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 border-t border-gray-200">
                            <button 
                                type="button"
                                wire:click="closeEditModal"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium"
                            >
                                Cancelar
                            </button>
                            <button 
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center"
                                wire:loading.attr="disabled"
                            >
                               
                                <span  >Actualizar Contacto</span>
                                
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>