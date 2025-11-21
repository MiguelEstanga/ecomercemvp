<div>
    @if($showImportModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeImportModal"></div>

                {{-- Modal --}}
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <h3 class="text-lg font-bold text-white">Importar Agencias desde Excel</h3>
                            </div>
                            <button wire:click="closeImportModal" class="text-white hover:text-gray-200">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Content --}}
                    <form wire:submit.prevent="importExcel">
                        <div class="p-6 space-y-6">
                            
                            {{-- Instrucciones --}}
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Instrucciones de Importación</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <ol class="list-decimal list-inside space-y-1">
                                                <li>Descarga la plantilla de Excel haciendo clic en el botón de abajo</li>
                                                <li>Completa el archivo con los datos de las agencias</li>
                                                <li>Sube el archivo completado en el formulario</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Botón Descargar Plantilla --}}
                            <div class="flex justify-center">
                                <button 
                                    type="button"
                                    wire:click="downloadTemplate"
                                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium flex items-center border-2 border-dashed border-gray-300"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Descargar Plantilla (CSV)
                                </button>
                            </div>

                            {{-- Formato de Archivo --}}
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Formato del Archivo</h4>
                                <div class="text-xs text-gray-600 space-y-1">
                                    <p><strong>Columnas requeridas:</strong></p>
                                    <ul class="list-disc list-inside ml-2 space-y-1">
                                        <li><code class="bg-gray-200 px-2 py-0.5 rounded">name</code> - Nombre de la agencia</li>
                                        <li><code class="bg-gray-200 px-2 py-0.5 rounded">address</code> - Dirección completa</li>
                                        <li><code class="bg-gray-200 px-2 py-0.5 rounded">is_active</code> - Estado (1 = activa, 0 = inactiva)</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Ejemplo de datos --}}
                            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                <h4 class="text-sm font-semibold text-yellow-800 mb-2">Ejemplo de Datos</h4>
                                <div class="text-xs font-mono bg-white p-3 rounded border border-yellow-300 overflow-x-auto">
                                    <table class="text-left">
                                        <thead class="border-b">
                                            <tr>
                                                <th class="pr-4">name</th>
                                                <th class="pr-4">address</th>
                                                <th>is_active</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="pr-4">Agencia Central</td>
                                                <td class="pr-4">Calle Principal 123, Ciudad</td>
                                                <td>1</td>
                                            </tr>
                                            <tr>
                                                <td class="pr-4">Agencia Norte</td>
                                                <td class="pr-4">Av. Norte 456, Zona Norte</td>
                                                <td>1</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Upload Area --}}
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-green-500 transition-colors">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div class="mt-4">
                                        <label class="cursor-pointer">
                                            <span class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors inline-block">
                                                Seleccionar Archivo
                                            </span>
                                            <input 
                                                type="file" 
                                                wire:model="excelFile"
                                                accept=".xlsx,.xls,.csv"
                                                class="hidden"
                                            />
                                        </label>
                                        <p class="mt-2 text-xs text-gray-500">
                                            Formatos soportados: XLSX, XLS, CSV (Máx. 2MB)
                                        </p>
                                    </div>
                                </div>

                                {{-- Preview del archivo seleccionado --}}
                                @if($excelFile)
                                    <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="h-5 w-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-sm font-medium text-green-800">
                                                    {{ $excelFile->getClientOriginalName() }}
                                                </span>
                                            </div>
                                            <button 
                                                type="button"
                                                wire:click="$set('excelFile', null)"
                                                class="text-green-600 hover:text-green-800"
                                            >
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                @error('excelFile')
                                    <div class="mt-4 p-3 bg-red-50 rounded-lg border border-red-200">
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    </div>
                                @enderror

                                {{-- Loading state --}}
                                <div wire:loading wire:target="excelFile" class="mt-4">
                                    <div class="flex items-center justify-center text-green-600">
                                        <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-sm">Procesando archivo...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                            <button 
                                type="button"
                                wire:click="closeImportModal"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium"
                            >
                                Cancelar
                            </button>
                            <button 
                                type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled"
                                wire:target="importExcel"
                                @if(!$excelFile) disabled @endif
                            >
                                <svg wire:loading wire:target="importExcel" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="importExcel">Importar Agencias</span>
                                <span wire:loading wire:target="importExcel">Importando...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>