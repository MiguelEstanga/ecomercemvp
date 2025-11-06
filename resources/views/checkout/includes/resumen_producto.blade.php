  <!-- Resumen del Producto -->
  <div class="bg-white rounded-lg shadow-sm p-6"  >
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">Resumen de Compra</h2>

      <div class="flex gap-4 pb-6 border-b border-gray-200">
          <!-- Imagen del Producto -->
          <div class="w-24 h-24 flex-shrink-0">
              @if ($product->product_imagens && count($product->product_imagens) > 0)a
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
