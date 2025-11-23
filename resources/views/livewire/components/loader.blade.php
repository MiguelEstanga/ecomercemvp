<div>
    {{-- Loader - Inicialmente oculto --}}
    <div id="loader" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white rounded-lg p-8 shadow-2xl">
            <div class="flex flex-col items-center space-y-4">
                {{-- Spinner Principal --}}
                <div class="relative">
                    <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-200"></div>
                    <div class="animate-spin rounded-full h-16 w-16 border-4 border-indigo-500 border-t-transparent absolute inset-0">
                    </div>
                </div>
                {{-- Texto de carga --}}
                <div class="text-center">
                    <p id="loader-message" class="text-gray-800 font-semibold text-lg">Cargando...</p>
                    <p class="text-gray-500 text-sm mt-1">Por favor espera un momento</p>
                </div>
                {{-- Indicador de progreso opcional --}}
                <div class="w-48 h-1 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-500 animate-pulse" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

 