<div>
    @if($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeEditModal"></div>

                {{-- Modal --}}
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    
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
                                    <h3 class="text-lg font-bold text-white">Editar Producto</h3>
                                    <p class="text-blue-100 text-sm">ID: #{{ $productId }}</p>
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
                    <form wire:submit.prevent="updateProduct">
                        <div class="p-6 max-h-[calc(100vh-250px)] overflow-y-auto">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                {{-- Nombre --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre del Producto <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="name"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                        placeholder="Ej: Laptop Dell XPS 15"
                                    />
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- SKU --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        SKU (Código)
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="sku"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sku') border-red-500 @enderror"
                                        placeholder="Ej: PROD-001"
                                    />
                                    @error('sku')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Categoría --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Categoría <span class="text-red-500">*</span>
                                    </label>
                                    <select 
                                        wire:model="category_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror bg-white"
                                    >
                                        <option value="">Selecciona una categoría</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Precio --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Precio <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                                        <input 
                                            type="number" 
                                            step="0.01"
                                            wire:model="price"
                                            class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror"
                                            placeholder="0.00"
                                        />
                                    </div>
                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Stock --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Stock <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="number" 
                                        wire:model="stock"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stock') border-red-500 @enderror"
                                        placeholder="0"
                                    />
                                    @error('stock')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Descripción --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Descripción
                                    </label>
                                    <textarea 
                                        wire:model="description"
                                        rows="4"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                        placeholder="Describe las características del producto..."
                                    ></textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Estado Activo --}}
                                <div class="md:col-span-2">
                                    <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <input 
                                            type="checkbox" 
                                            wire:model="is_active"
                                            id="is_active_edit"
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        />
                                        <label for="is_active_edit" class="ml-3 text-sm font-medium text-gray-700">
                                            Producto activo y visible para los clientes
                                        </label>
                                    </div>
                                </div>

                                {{-- Imágenes Existentes --}}
                                @if(!empty($existingImages))
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Imágenes Actuales
                                    </label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach($existingImages as $image)
                                            <div class="relative group">
                                                @php
                                                    $cleanPath = str_replace('public/', '', $image['path']);
                                                @endphp
                                                <img 
                                                    src="/storage/{{ $cleanPath }}" 
                                                    class="w-full h-32 object-cover rounded-lg border-2 border-gray-200"
                                                >
                                                @if($image['is_primary'])
                                                    <span class="absolute top-2 left-2 px-2 py-1 text-xs font-semibold bg-blue-600 text-white rounded">
                                                        Principal
                                                    </span>
                                                @endif
                                                <button 
                                                    type="button"
                                                    wire:click="markImageForDeletion({{ $image['id'] }})"
                                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                                    title="Eliminar imagen"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                {{-- Subir Nuevas Imágenes --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Agregar Más Imágenes
                                    </label>
                                    
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-500 transition-colors">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <div class="mt-4">
                                                <label class="cursor-pointer">
                                                    <span class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-block">
                                                        Seleccionar Imágenes
                                                    </span>
                                                    <input 
                                                        type="file" 
                                                        wire:model="images"
                                                        multiple
                                                        accept="image/*"
                                                        class="hidden"
                                                    />
                                                </label>
                                                <p class="mt-2 text-xs text-gray-500">
                                                    PNG, JPG, WEBP hasta 2MB cada una
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Preview de nuevas imágenes --}}
                                        @if($images)
                                            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                                @foreach($images as $index => $image)
                                                    <div class="relative group">
                                                        <img 
                                                            src="{{ $image->temporaryUrl() }}" 
                                                            class="w-full h-32 object-cover rounded-lg border-2 border-green-300"
                                                        >
                                                        <span class="absolute top-2 left-2 px-2 py-1 text-xs font-semibold bg-green-600 text-white rounded">
                                                            Nueva
                                                        </span>
                                                        <button 
                                                            type="button"
                                                            wire:click="$set('images.{{ $index }}', null)"
                                                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                                        >
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- Loading state --}}
                                        <div wire:loading wire:target="images" class="mt-4">
                                            <div class="flex items-center justify-center text-blue-600">
                                                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span class="text-sm">Procesando imágenes...</span>
                                            </div>
                                        </div>

                                        @error('images.*')
                                            <div class="mt-4 p-3 bg-red-50 rounded-lg border border-red-200">
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

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
                                 
                                wire:target="updateProduct, images"
                            >
                               
                                <span  wire:target="updateProduct">Actualizar Producto</span>
                                 
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>