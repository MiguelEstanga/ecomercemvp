<?php 
// app/Helpers/FileHelper.php

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Procesa la subida de un archivo desde la Request y retorna su ruta de almacenamiento.
 *
 * @param Request $request La instancia de la petición.
 * @param string $fieldName El nombre del campo del archivo en la petición (ej: 'document_image').
 * @param string $storagePath La carpeta de destino (ej: 'order_documents').
 * @param string $disk El disco de almacenamiento (ej: 'public').
 * @return string|null La ruta relativa del archivo almacenado, o null si no hay archivo válido.
 */
function uploadAndGetPath(
    Request $request, 
    string $fieldName, 
    string $storagePath = 'uploads', 
    string $disk = 'public'
): ?string 
{
    // 1. Verificar si el archivo existe y es válido
    if ($request->hasFile($fieldName) && $request->file($fieldName)->isValid()) {
        
        /** @var UploadedFile $file */
        $file = $request->file($fieldName);
        
        // 2. Almacenar el archivo
        $path = $file->store($storagePath, $disk);
        
        // 3. Retornar la ruta relativa
        return $path;
    }
    
    return null;
}

// Para la reversión: Helper para eliminar archivos subidos
/**
 * Elimina uno o más archivos subidos.
 */
function deleteUploadedFiles(array $paths, string $disk = 'public'): void
{
    if (!empty($paths)) {
        Storage::disk($disk)->delete($paths);
    }
}