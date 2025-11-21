<?php

namespace App\Services;

use Illuminate\Support\Collection;
// Importamos la clase principal de la nueva librería
use Rap2hpoutre\FastExcel\FastExcel;
use Throwable;

class ExelServices
{
    /**
     * Cargar archivo Excel/CSV en una Collection de Laravel.
     * Retorna una Collection donde cada elemento es un array de datos.
     */
    public function load($file): Collection
    {
        // El método import() de FastExcel devuelve directamente una Collection de Laravel.
        // Se asume que $file es la ruta del archivo o un objeto UploadedFile
        return (new FastExcel())->import($file);
    }

    /**
     * Importa un archivo y guarda cada fila en un modelo.
     *
     * @param mixed $file Archivo (ruta o UploadedFile)
     * @param string $model Model::class
     * @param array|null $columnMapping ['col_excel_header' => 'col_db_name']
     */
    public function importToDatabase($file, string $model, array $columnMapping = null): array
    {
        // 1. Cargar la colección
        $collection = $this->load($file);

        $inserted = 0;
        $errors = [];

        // FastExcel usa los encabezados como claves si están presentes, 
        // lo que simplifica la iteración y el mapeo.
        foreach ($collection as $index => $row) {

            // $row ya es un array asociativo (o un objeto, dependiendo de la configuración)
            $rowArray = (array) $row;

            try {
                $data = $columnMapping
                    ? $this->mapColumns($rowArray, $columnMapping)
                    : $rowArray;

                // Crear el modelo
                $model::create($data);
                $inserted++;
            } catch (Exception $e) {
                $errors[] = [
                    // El índice comienza en 0, pero la fila real es $index + 1
                    'row' => $index + 1,
                    'error' => $e->getMessage(),
                    'data' => $rowArray,
                ];
            }
        }

        return [
            'inserted' => $inserted,
            'errors' => $errors,
        ];
    }

    /**
     * Mapear columnas usando los encabezados del archivo como clave.
     * La clave de $row es el nombre del encabezado (Ej: 'Nombre Completo').
     */
    private function mapColumns(array $row, array $mapping): array
    {
        $data = [];

        // $mapping debe ser: ['Encabezado del Excel' => 'columna_en_db']
        foreach ($mapping as $excelColumnHeader => $dbColumn) {
            // Aseguramos que la clave del Excel exista en la fila
            $data[$dbColumn] = $row[$excelColumnHeader] ?? null;
        }

        return $data;
    }
}
