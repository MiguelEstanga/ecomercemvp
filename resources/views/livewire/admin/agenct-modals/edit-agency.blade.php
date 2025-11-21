<div>
    @if($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeEditModal"></div>

                {{-- Modal --}}
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-white">Editar Agencia de Retiro</h3>
                            <button wire:click="closeEditModal" class="text-white hover:text-gray-200">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Content --}}
                    <form wire:submit.prevent="updateAgency">
                        <div class="p-6 space-y-4">
                            
                            {{-- ID (solo lectura) --}}
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                    <span class="text-sm text-gray-500">ID de Agencia:</span>
                                    <span class="ml-2 text-sm font-semibold text-gray-900">#{{ $agencyId }}</span>
                                </div>
                            </div>

                            {{-- Nombre --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre de la Agencia <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                    placeholder="Ej: Agencia Central"
                                />
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Dirección --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Dirección <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    wire:model="address"
                                    rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror"
                                    placeholder="Ej: Calle Principal 123, Colonia Centro, Ciudad"
                                ></textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Estado Activo --}}
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <input 
                                    type="checkbox" 
                                    wire:model="is_active"
                                    id="is_active_edit"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                />
                                <label for="is_active_edit" class="ml-2 text-sm font-medium text-gray-700">
                                    Agencia activa
                                </label>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
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
                                 
                                <span wire:loading.remove wire:target="updateAgency">Actualizar Agencia</span>
                               
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>