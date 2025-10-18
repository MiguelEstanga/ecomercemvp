@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session()->has('success'))
            <x-alert-component type="success" message="{{ session('success') }}" />
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Resumen del Producto -->
            <div class="bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Resumen de Compra</h2>

                <div class="flex gap-4 pb-6 border-b border-gray-200">
                    <!-- Imagen del Producto -->
                    <div class="w-24 h-24 flex-shrink-0">
                        @if ($product->product_imagens && count($product->product_imagens) > 0)
                            @php
                                $mainImage =
                                    collect($product->product_imagens)->firstWhere('is_main', true) ??
                                    $product->product_imagens[0];
                            @endphp
                            <img src="{{ $mainImage->path }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover rounded-lg">
                        @else
                            <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400 text-xs">Sin imagen</span>
                            </div>
                        @endif
                    </div>

                    <!-- Información del Producto -->
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($product->description, 80) }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-sm text-gray-600">Cantidad: <span
                                    class="font-semibold">{{ $request->quantity ?? 1 }}</span></span>
                            <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="mt-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Subtotal</span>
                        <span>${{ number_format($product->price * ($request->quantity ?? 1), 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600 mb-4">
                        <span>Envío</span>
                        <span>A calcular</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold text-gray-900 pt-4 border-t border-gray-200">
                        <span>Total</span>
                        <span>${{ number_format($product->price * ($request->quantity ?? 1), 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Formulario de Checkout -->
            <div class=" bg-gray-800 rounded-lg p-6 shadow-sm "> 
                <h2 class="text-2xl font-semibold text-white  mb-6">Información de Pago</h2>

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data"
                    id="checkoutForm">
                    @csrf
                    <input type="hidden" name="quantity" value="{{ $request->quantity ?? 1 }}">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <!-- Método de Pago -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium   mb-2">
                            Método de Pago <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_method_id" required
                            class="w-full px-4 py-2 border bg-gray-800 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('payment_method_id') border-red-500 @enderror">
                            <option value="">Seleccionar método</option>
                            <option value="1" {{ old('payment_method_id') == 1 ? 'selected' : '' }}>Transferencia
                                Bancaria</option>
                            <option value="2" {{ old('payment_method_id') == 2 ? 'selected' : '' }}>Depósito</option>
                            <option value="3" {{ old('payment_method_id') == 3 ? 'selected' : '' }}>Pago Móvil
                            </option>
                        </select>
                        @error('payment_method_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-white mb-2">
                            Método de Pago <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_method_id" required
                            class="w-full px-4 py-2 border bg-gray-800 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('payment_method_id') border-red-500 @enderror">
                            <option value="">Seleccionar método</option>
                            <option value="1" {{ old('payment_method_id') == 1 ? 'selected' : '' }}>Transferencia
                                Bancaria</option>
                            <option value="2" {{ old('payment_method_id') == 2 ? 'selected' : '' }}>Depósito</option>
                            <option value="3" {{ old('payment_method_id') == 3 ? 'selected' : '' }}>Pago Móvil
                            </option>
                        </select>
                        @error('payment_method_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium   mb-2">
                            Numero de referencia <span class="text-red-500">*</span>
                        </label>
                        <input
                            value="{{ old('reference_number') }}"
                            name="reference_number"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('reference_number') border-red-500 @enderror" />
                        @error('reference_number')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium   mb-2">
                            Numero de telefono <span class="text-red-500">*</span>
                        </label>
                        <input
                            value="{{ old('phone_number') }}"
                            name="phone_number"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mt') border-red-500 @enderror" />
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Agencia de Retiro -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium   mb-2">
                            Agencia de Retiro <span class="text-red-500">*</span>
                        </label>
                        <select name="pickup_agency_id" required
                            class="w-full px-4 py-2 border bg-gray-800  rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('pickup_agency_id') border-red-500 @enderror">
                            <option value="">Seleccionar agencia</option>
                            @foreach ($agencies as $agency)
                                <option value="{{ $agency->id }}"
                                    {{ old('pickup_agency_id') == $agency->id ? 'selected' : '' }}>
                                    {{ $agency->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('pickup_agency_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dirección de Envío -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Dirección de Envío <span class="text-red-500">*</span>
                        </label>
                        <textarea name="shipping_address" rows="3" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_address') border-red-500 @enderror"
                            placeholder="Ingresa tu dirección completa">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Imagen Documento -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Imagen de Documento (DNI/Cédula) <span class="text-red-500">*</span>
                        </label>
                        <div id="documentImageArea"
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition cursor-pointer @error('document_image') border-red-500 @enderror">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="document_image"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                        <span id="documentImageLabel">Subir archivo</span>
                                        <input id="document_image" name="document_image" type="file" accept="image/*"
                                            required class="sr-only">
                                    </label>
                                    <p class="pl-1">o arrastra y suelta</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG hasta 5MB</p>
                            </div>
                        </div>
                        <!-- Preview de imagen -->
                        <div id="documentImagePreview" class="mt-3 hidden">
                            <img src="" alt="Preview" class="max-w-full h-48 object-cover rounded-lg mx-auto">
                            <button type="button" onclick="removeDocumentImage()"
                                class="mt-2 text-sm text-red-600 hover:text-red-800">
                                ✕ Eliminar imagen
                            </button>
                        </div>
                        @error('document_image')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Imagen Comprobante -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Comprobante de Pago <span class="text-red-500">*</span>
                        </label>
                        <div id="paymentProofArea"
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition cursor-pointer @error('payment_proof') border-red-500 @enderror">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="payment_proof"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                        <span id="paymentProofLabel">Subir archivo</span>
                                        <input id="payment_proof" name="payment_proof" type="file" accept="image/*"
                                            required class="sr-only">
                                    </label>
                                    <p class="pl-1">o arrastra y suelta</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG hasta 5MB</p>
                            </div>
                        </div>
                        <!-- Preview de imagen -->
                        <div id="paymentProofPreview" class="mt-3 hidden">
                            <img src="" alt="Preview" class="max-w-full h-48 object-cover rounded-lg mx-auto">
                            <button type="button" onclick="removePaymentProof()"
                                class="mt-2 text-sm text-red-600 hover:text-red-800">
                                ✕ Eliminar imagen
                            </button>
                        </div>
                        @error('payment_proof')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botón de Confirmar -->
                    <button type="submit" id="submitBtn"
                        class="w-full bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-200 shadow-md hover:shadow-lg disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Confirmar Pedido
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Preview de imagen de documento
        document.getElementById('document_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validar tamaño (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('El archivo es demasiado grande. Máximo 5MB.');
                    this.value = '';
                    return;
                }

                // Validar tipo
                if (!file.type.match('image.*')) {
                    alert('Por favor selecciona una imagen válida.');
                    this.value = '';
                    return;
                }

                // Mostrar nombre
                document.getElementById('documentImageLabel').textContent = file.name;

                // Mostrar preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('documentImagePreview');
                    preview.querySelector('img').src = e.target.result;
                    preview.classList.remove('hidden');
                    document.getElementById('documentImageArea').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Preview de comprobante de pago
        document.getElementById('payment_proof').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validar tamaño (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('El archivo es demasiado grande. Máximo 5MB.');
                    this.value = '';
                    return;
                }

                // Validar tipo
                if (!file.type.match('image.*')) {
                    alert('Por favor selecciona una imagen válida.');
                    this.value = '';
                    return;
                }

                // Mostrar nombre
                document.getElementById('paymentProofLabel').textContent = file.name;

                // Mostrar preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('paymentProofPreview');
                    preview.querySelector('img').src = e.target.result;
                    preview.classList.remove('hidden');
                    document.getElementById('paymentProofArea').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Función para eliminar imagen de documento
        function removeDocumentImage() {
            document.getElementById('document_image').value = '';
            document.getElementById('documentImagePreview').classList.add('hidden');
            document.getElementById('documentImageArea').classList.remove('hidden');
            document.getElementById('documentImageLabel').textContent = 'Subir archivo';
        }

        // Función para eliminar comprobante
        function removePaymentProof() {
            document.getElementById('payment_proof').value = '';
            document.getElementById('paymentProofPreview').classList.add('hidden');
            document.getElementById('paymentProofArea').classList.remove('hidden');
            document.getElementById('paymentProofLabel').textContent = 'Subir archivo';
        }

        // Validación antes de enviar
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const documentImage = document.getElementById('document_image').files[0];
            const paymentProof = document.getElementById('payment_proof').files[0];

            if (!documentImage || !paymentProof) {
                e.preventDefault();
                alert('Por favor, sube ambas imágenes requeridas.');
                return false;
            }

            // Deshabilitar botón para evitar doble submit
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').textContent = 'Procesando...';
        });
    </script>
@endsection
