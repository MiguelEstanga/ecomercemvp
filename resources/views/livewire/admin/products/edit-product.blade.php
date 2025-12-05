<div>
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <!-- Overlay -->
                <div class="fixed inset-0 bg-gray-800 bg-opacity-70 transition-opacity" wire:click="closeEditModal"></div>

                <!-- Modal -->
                <div
                    class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">

                    <!-- Header con gradiente -->
                    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-5">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                <div class="bg-white bg-opacity-25 rounded-xl p-3">
                                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white">Editar Producto</h3>
                                    <p class="text-indigo-100 text-sm font-medium">ID: #{{ $productId }}</p>
                                </div>
                            </div>
                            <button wire:click="closeEditModal" class="text-white hover:text-indigo-200 transition">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Formulario -->
                    <form action="{{ route('admin.productos.update', $product->id) }}" method="POST" 
                            enctype="multipart/form-data"
                        >
                        @csrf
                        
                        <div class="p-6 space-y-8 max-h-[calc(100vh-280px)] overflow-y-auto">

                            <!-- 1. Información Básica -->
                            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <span class="bg-blue-600 text-white rounded-lg px-3 py-1 text-sm mr-3">1</span>
                                    Información Básica
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <x-form-input label="Nombre del Producto" name="name" wire:model.lazy="name"
                                        placeholder="Ej: Camiseta Premium Azul" :error="$errors->first('name')" />

                                    <x-form-input label="SKU (Código)" name="SKU" wire:model.lazy="sku"
                                        placeholder="Ej: CAM-AZUL-001" :error="$errors->first('sku')" />
                                </div>

                                <div class="mt-5">
                                    <x-select-input label="Categoría" name="category_id" wire:model="category_id"
                                        :options="$categories" :error="$errors->first('category_id')" />
                                </div>
                            </div>

                            <!-- 2. Precio y Stock -->
                            <div class="bg-green-50 rounded-xl p-5 border border-green-200">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <span class="bg-green-600 text-white rounded-lg px-3 py-1 text-sm mr-3">2</span>
                                    Precio y Disponibilidad
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                    <x-form-input type="number" step="0.01" label="Precio de Venta" name="price"
                                        wire:model.lazy="price" placeholder="0.00" :error="$errors->first('price')" />

                                    <x-form-input type="number" label="Stock Actual" name="stock"
                                        wire:model.lazy="stock" placeholder="0" :error="$errors->first('stock')" />

                                    <div class="flex items-end">
                                        <div class="w-full">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" wire:model="is_active"
                                                    class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 h-5 w-5">
                                                <span class="ml-3 text-gray-700 font-medium">{{ $is_active ? 'Activo' : 'Inactivo' }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Descripción -->
                            <div class="bg-amber-50 rounded-xl p-5 border border-amber-200">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <span class="bg-amber-600 text-white rounded-lg px-3 py-1 text-sm mr-3">3</span>
                                    Descripción del Producto
                                </h4>
                                <x-textarea-input label="Descripción completa (opcional)" name="description"
                                    wire:model.lazy="description" rows="4"
                                    placeholder="Detalla características, materiales, tallas disponibles..."
                                    :error="$errors->first('description')" />
                            </div>

                            <!-- 4. Imágenes del Producto -->
                            <div class="bg-purple-50 rounded-xl p-5 border border-purple-200">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <span class="bg-purple-600 text-white rounded-lg px-3 py-1 text-sm mr-3">4</span>
                                    Imágenes del Producto
                                </h4>
                                
                                <div class="space-y-4">
                                    <!-- Preview de imagen actual -->
                                    @if($product && $product->product_imagens->first())
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Imagen Actual</label>
                                            <div class="relative inline-block">
                                                <img src="{{ asset('storage/' . $product->product_imagens->first()->path) }}" 
                                                     alt="Imagen actual" 
                                                     id="current-image"
                                                     class="h-32 w-32 object-cover rounded-lg border-2 border-gray-300">
                                            </div>
                                        </div>
                                    @endif

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ ($product && $product->product_imagens->first()) ? 'Cambiar Imagen' : 'Subir Imagen' }}
                                        </label>
                                        <input type="file"
                                               name="imagen"
                                               id="imagen-input"
                                               accept="image/*"
                                               class="block w-full text-sm text-gray-500
                                                      file:mr-4 file:py-2 file:px-4
                                                      file:rounded-lg file:border-0
                                                      file:text-sm file:font-semibold
                                                      file:bg-purple-600 file:text-white
                                                      hover:file:bg-purple-700
                                                      cursor-pointer">
                                        
                                        @error('imagen')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Preview de nueva imagen -->
                                    <div id="preview-container" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Vista Previa</label>
                                        <img id="preview-image"
                                             alt="Preview"
                                             class="h-32 w-32 object-cover rounded-lg border-2 border-purple-300">
                                    </div>
                                </div>

                                <p class="text-xs text-gray-500 mt-4">
                                    Formatos aceptados: JPG, PNG, GIF. Tamaño máximo: 2MB.
                                </p>
                            </div>

                        </div>

                        <!-- Footer con botones -->
                        <div class="bg-gray-100 px-6 py-5 border-t border-gray-300 flex justify-between items-center">
                            <button type="button" wire:click="closeEditModal"
                                class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition shadow-sm">
                                Cancelar
                            </button>

                            <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-lg hover:from-indigo-700 hover:to-blue-700 font-semibold transition shadow-lg flex items-center space-x-2 disabled:opacity-70">
                                
                                <span wire:loading.remove wire:target="updateProduct">
                                    Actualizar Producto
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Esperar a que Livewire monte el componente
        const setupImagePreview = () => {
            const inputFile = document.getElementById('imagen-input');
            
            if (inputFile) {
                inputFile.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    const previewContainer = document.getElementById('preview-container');
                    const previewImage = document.getElementById('preview-image');
                    
                    if (file) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            previewContainer.classList.remove('hidden');
                        }
                        
                        reader.readAsDataURL(file);
                    } else {
                        previewContainer.classList.add('hidden');
                    }
                });
            }
        };

        // Ejecutar al cargar y después de cada actualización de Livewire
        setupImagePreview();
        
        // Re-ejecutar cuando Livewire actualice el DOM
        window.addEventListener('livewire:load', setupImagePreview);
        if (window.Livewire) {
            window.Livewire.hook('message.processed', () => {
                setupImagePreview();
            });
        }
    });
</script>