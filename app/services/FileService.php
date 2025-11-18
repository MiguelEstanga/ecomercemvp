<?php

namespace App\services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileService
{
    /**
     * Subir archivo al storage
     * 
     * @param UploadedFile $file
     * @param string $folder Carpeta donde guardar (ej: 'documents', 'profiles', 'payments')
     * @param string $disk Disco de storage (public, local, s3)
     * @param string|null $oldPath Path del archivo anterior para eliminar
     * @return string|false Path del archivo o false en caso de error
     */
    public function upload(UploadedFile $file, string $folder = 'uploads', string $disk = 'public', ?string $oldPath = null)
    {
        try {
            // Si hay un archivo anterior, eliminarlo
            if ($oldPath) {
                $this->delete($oldPath, $disk);
            }

            // Generar nombre único para el archivo
            $filename = $this->generateUniqueFileName($file);
            
            // Guardar el archivo
            $path = $file->storeAs($folder, $filename, $disk);
            
            Log::info("Archivo subido exitosamente: {$path}");
            
            return $path;
        } catch (\Exception $e) {
            Log::error('Error al subir archivo: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Subir múltiples archivos
     * 
     * @param array $files Array de UploadedFile
     * @param string $folder
     * @param string $disk
     * @return array Array de paths
     */
    public function uploadMultiple(array $files, string $folder = 'uploads', string $disk = 'public'): array
    {
        $paths = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $path = $this->upload($file, $folder, $disk);
                if ($path) {
                    $paths[] = $path;
                }
            }
        }
        
        return $paths;
    }

    /**
     * Eliminar archivo del storage
     * 
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public function delete(string $path, string $disk = 'public'): bool
    {
        try {
            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
                Log::info("Archivo eliminado: {$path}");
                return true;
            }
            
            Log::warning("Archivo no encontrado para eliminar: {$path}");
            return false;
        } catch (\Exception $e) {
            Log::error('Error al eliminar archivo: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar múltiples archivos
     * 
     * @param array $paths
     * @param string $disk
     * @return int Cantidad de archivos eliminados
     */
    public function deleteMultiple(array $paths, string $disk = 'public'): int
    {
        $deleted = 0;
        
        foreach ($paths as $path) {
            if ($this->delete($path, $disk)) {
                $deleted++;
            }
        }
        
        return $deleted;
    }

    /**
     * Obtener URL pública del archivo
     * 
     * @param string $path
     * @param string $disk
     * @return string|null
     */
    // public function getUrl(string $path, string $disk = 'public'): ?string
    // {
    //     try {
    //         if (Storage::disk($disk)->exists($path)) {
    //             return Storage::disk($disk)->url($path);
    //         }
    //         return null;
    //     } catch (\Exception $e) {
    //         Log::error('Error al obtener URL: ' . $e->getMessage());
    //         return null;
    //     }
    // }

    /**
     * Verificar si el archivo existe
     * 
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public function exists(string $path, string $disk = 'public'): bool
    {
        return Storage::disk($disk)->exists($path);
    }

    /**
     * Obtener tamaño del archivo en bytes
     * 
     * @param string $path
     * @param string $disk
     * @return int|false
     */
    public function getSize(string $path, string $disk = 'public')
    {
        try {
            if ($this->exists($path, $disk)) {
                return Storage::disk($disk)->size($path);
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Error al obtener tamaño: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generar nombre único para el archivo
     * 
     * @param UploadedFile $file
     * @return string
     */
    protected function generateUniqueFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = Str::slug($originalName);
        $timestamp = now()->timestamp;
        $random = Str::random(8);
        
        return "{$safeName}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Validar tipo de archivo
     * 
     * @param UploadedFile $file
     * @param array $allowedMimes
     * @return bool
     */
    public function validateMimeType(UploadedFile $file, array $allowedMimes): bool
    {
        return in_array($file->getMimeType(), $allowedMimes);
    }

    /**
     * Validar tamaño del archivo
     * 
     * @param UploadedFile $file
     * @param int $maxSizeInMB
     * @return bool
     */
    public function validateSize(UploadedFile $file, int $maxSizeInMB = 5): bool
    {
        $maxSizeInBytes = $maxSizeInMB * 1024 * 1024;
        return $file->getSize() <= $maxSizeInBytes;
    }

    /**
     * Reemplazar archivo (eliminar el viejo y subir el nuevo)
     * 
     * @param UploadedFile $newFile
     * @param string|null $oldPath
     * @param string $folder
     * @param string $disk
     * @return string|false
     */
    public function replace(UploadedFile $newFile, ?string $oldPath, string $folder = 'uploads', string $disk = 'public')
    {
        return $this->upload($newFile, $folder, $disk, $oldPath);
    }
}