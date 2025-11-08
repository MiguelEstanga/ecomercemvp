@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session()->has('success'))
            <x-alert-component type="success" message="{{ session('success') }}" />
        @endif
 
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            @include('checkout.includes.resumen_producto')

            <!-- Formulario de Checkout -->
            <div class=" bg-white rounded-lg p-6 shadow-sm ">
                <h2 class="text-2xl font-semibold text-black  mb-6">Información de Pago</h2>

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>s
                    </div>
                @endif

                <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
                    @csrf
                    <input type="hidden" name="quantity" value="{{ $request->quantity ?? 1 }}">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <!-- Método de Pago -->
                    <x-select-input name="payment_method_id" label="Método de Pago" :options="[
                        ['id' => 1, 'name' => 'Transferencia Bancaria'],
                        ['id' => 2, 'name' => 'Depósito'],
                        ['id' => 3, 'name' => 'Pago Móvil'],
                    ]"
                        placeholder="Seleccionar método" required />
                    <x-text-input name="reference_number" label="Número de referencia"
                        placeholder="Ingresa el número de referencia" required />

                    <x-text-input name="phone_number" label="Numero de telefono" placeholder="04141234567" required />

                    <x-select-input name="payment_method_id" label="Agencia de Retiro" :options="$agencies"
                        placeholder="Agencia de Retiro" required />

                    <x-textarea-input name="shipping_address" label="Dirección de Envío"
                        placeholder="Ingresa tu dirección completa" rows="3" required />


                    <x-file-upload name="document_image" label="Imagen de Documento (DNI/Cédula)" accept="image/*"
                        maxSize="5MB" allowedFormats="PNG, JPG" required />

                    <x-file-upload name="payment_proof" label="Comprobante de Pago" accept="image/*" maxSize="5MB"
                        allowedFormats="PNG, JPG" required />



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
