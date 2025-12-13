{{-- Datos Bancarios --}}
<div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg shadow-sm p-6 border border-blue-200">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
        </svg>
        Datos para Transferencia
    </h3>
    
    @if($contacto && $contacto->count() > 0)
        <div class="space-y-4">
            @foreach($contacto as $contact)
                <div class="bg-white rounded-lg p-4 border border-blue-200">
                    {{-- Header del Banco --}}
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center">
                            <div class="bg-blue-100 rounded-lg p-2 mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $contact->nombre_banco }}</h4>
                                @if($contact->codigo_banco)
                                    <p class="text-xs text-gray-500">Código: {{ $contact->codigo_banco }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- Número de Cuenta --}}
                    @if($contact->cuenta_banco)
                        <div class="mb-3">
                            <label class="text-xs text-gray-500 block mb-1">Número de Cuenta</label>
                            <div class="flex items-center justify-between bg-gray-50 rounded px-3 py-2 group">
                                <span class="font-mono text-sm font-medium text-gray-900" id="cuenta-{{ $contact->id }}">
                                    {{ $contact->cuenta_banco }}
                                </span>
                                <button 
                                    type="button" 
                                    onclick="copiarTexto('cuenta-{{ $contact->id }}')" 
                                    class="text-blue-600 hover:text-blue-700 transition-colors"
                                    title="Copiar número de cuenta"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Teléfono del Banco --}}
                    @if($contact->telefono_banco)
                        <div>
                            <label class="text-xs text-gray-500 block mb-1">Teléfono del Banco</label>
                            <div class="flex items-center text-sm text-gray-700 bg-gray-50 rounded px-3 py-2">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $contact->telefono_banco }}
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        {{-- Nota Importante --}}
        <div class="mt-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs text-yellow-800">
                        <strong>Importante:</strong> Después de realizar la transferencia, sube el comprobante en el formulario y envíanos el número de referencia por WhatsApp.
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-4 bg-white rounded-lg">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <p class="text-sm text-gray-500">No hay datos bancarios disponibles</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
function copiarTexto(elementId) {
    const elemento = document.getElementById(elementId);
    const texto = elemento.textContent.trim();
    
    navigator.clipboard.writeText(texto).then(() => {
        // Crear notificación
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center animate-fade-in';
        notification.innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            ¡Número de cuenta copiado!
        `;
        document.body.appendChild(notification);
        
        // Remover después de 3 segundos
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }).catch(err => {
        console.error('Error al copiar:', err);
        alert('No se pudo copiar el texto. Por favor, cópialo manualmente.');
    });
}
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endpush