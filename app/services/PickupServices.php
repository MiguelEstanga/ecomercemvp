<?php

namespace App\services;

use App\Models\PinchupAgencies as Agency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\services\ExelServices;
use Illuminate\Support\Facades\Cache;


class PickupServices
{
    private $excelService;
    public function __construct(ExelServices $excelService)
    {
        $this->excelService = $excelService;
    }
    public function get($search = null, $statusFilter = null, $sortField = 'created_at', $sortDirection = 'desc')
    {
        $query = Agency::query();

        // Buscar por nombre o dirección
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($statusFilter !== null && $statusFilter !== '') {
            $query->where('is_active', $statusFilter);
        }

        // Ordenamiento
        $query->orderBy($sortField, $sortDirection);

        return $query; // devuelve el builder
    }

    public function findOrFail($id)
    {
        return Agency::findOrFail($id);
    }

    public function create($data)
    {
        return Agency::create($data);
    }

    public function createOrUpdate($data)
    {
        $agency = Agency::where('id', $data['id'])->first();
        if ($agency) {
            $agency->update($data);
        } else {
            $agency = Agency::create($data);
        }
        return $agency;
    }

    public function update($id, $data)
    {
        return Agency::where('id', $id)->update($data);
    }

    public function toggleStatus($id)
    {
        return Agency::where('id', $id)->update(['active' => !$id->active]);
    }
    public function createWithExel($file)
    {
        try {
            $inserted = $this->excelService->importToDatabase($file, Agency::class);
            Log::info('Agencias importadas correctamente', ['inserted' => $inserted]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error al importar agencias: ' . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        return Agency::where('id', $id)->delete();
    }

    public function stats()
    {
        return [
            'total' => Agency::count(),
            'active' => Agency::where('is_active', true)->count(),
            'inactive' => Agency::where('is_active', false)->count(),
        ];
    }
}
