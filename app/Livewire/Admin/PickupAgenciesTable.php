<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\PinchupAgencies;
use Illuminate\Support\Facades\Log;


use App\services\PickupServices;
use App\services\ExelServices;

class PickupAgenciesTable extends Component
{
    use WithPagination, WithFileUploads;

    // Propiedades de búsqueda y filtros
    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;

    // Propiedades del formulario
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showImportModal = false;
    public $agencyId;
    public $name;
    public $address;
    public $is_active = true;

    // Importación
    public $excelFile;

    // Ordenamiento
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected  $pickupService;

    public function boot()
    {
        $this->pickupService =  new PickupServices(new ExelServices());
    }

    protected $listeners = [
        'refreshTable' => '$refresh',
    ];

    // Reglas de validación
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre es obligatorio',
        'address.required' => 'La dirección es obligatoria',
    ];

    // Resetear paginación al buscar
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    // Ordenar
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
            $this->sortField = $field;
        }
    }

    // Limpiar filtros
    public function clearFilters()
    {
        $this->reset(['search', 'statusFilter']);
        $this->resetPage();
    }

    // Abrir modal de crear
    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    // Cerrar modal de crear
    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    // Crear agencia
    public function createAgency()
    {
        $this->validate();
        $this->dispatch('startLoading');
        try {
            $this->pickupService->create([
                'name' => $this->name,
                'address' => $this->address,
                'is_active' => $this->is_active,
            ]);

            session()->flash('success', 'Agencia creada exitosamente');
            $this->closeCreateModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la agencia: ' . $e->getMessage());
        } finally {
            $this->dispatch('stopLoading');
        }
    }

    // Abrir modal de editar
    public function openEditModal($id)
    {
        $agency = $this->pickupService->findOrFail($id);

        $this->agencyId = $agency->id;
        $this->name = $agency->name;
        $this->address = $agency->address;
        $this->is_active = $agency->is_active;

        $this->showEditModal = true;
    }

    // Cerrar modal de editar
    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    // Actualizar agencia
    public function updateAgency()
    {
        $this->validate();

        try {
            $agency =  $this->pickupService->update($this->agencyId, [
                'name' => $this->name,
                'address' => $this->address,
                'is_active' => $this->is_active,
            ]);


            session()->flash('success', 'Agencia actualizada exitosamente');
            $this->closeEditModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar la agencia: ' . $e->getMessage());
        }
    }

    // Cambiar estado
    public function toggleStatus($id)
    {
        try {
            $agency =  $this->pickupService->toggleStatus($id);

            session()->flash('success', "Agencia {$agency->name} {$agency->is_active} exitosamente");
        } catch (\Exception $e) {
            Log::info('Error al cambiar el estado de la agencia: ' . $e->getMessage());
            session()->flash('error', 'Error al cambiar el estado de la agencia');
        }
    }

    // Eliminar agencia
    public function deleteAgency($id)
    {
        $this->dispatch('startLoading');
        try {
            $this->pickupService->delete($id);

            session()->flash('success', 'Agencia eliminada exitosamente');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la agencia');
        } finally {
            $this->dispatch('stopLoading');
        }
    }

    // Abrir modal de importación
    public function openImportModal()
    {
        $this->resetForm();
        $this->showImportModal = true;
    }

    // Cerrar modal de importación
    public function closeImportModal()
    {
        $this->showImportModal = false;
        $this->reset(['excelFile']);
        $this->resetValidation();
    }

    // Importar Excel
    public function importExcel()
    {
        $this->validate([
            'excelFile' => 'required|mimes:xlsx,xls,csv|max:2048',
        ], [
            'excelFile.required' => 'Debes seleccionar un archivo',
            'excelFile.mimes' => 'El archivo debe ser Excel (.xlsx, .xls) o CSV',
            'excelFile.max' => 'El archivo no debe pesar más de 2MB',
        ]);

        try {
            $this->pickupService->createWithExel($this->excelFile->getRealPath() , PinchupAgencies::class);

            session()->flash('success', 'Agencias importadas exitosamente');
            $this->closeImportModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al importar el archivo: ' . $e->getMessage());
        }
    }

    // Descargar plantilla
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="plantilla_agencias.csv"',
        ];

        $columns = ['name', 'address', 'is_active'];
        $example = ['Agencia Central', 'Calle Principal 123, Ciudad', '1'];

        $callback = function () use ($columns, $example) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, $example);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Resetear formulario
    private function resetForm()
    {
        $this->reset([
            'agencyId',
            'name',
            'address',
            'is_active',
            'excelFile'
        ]);
    }

    public function render()
    {

        $agencies = $this->pickupService->get($this->search, $this->statusFilter)->paginate($this->perPage);

        // Estadísticas
        $stats = $this->pickupService->stats();

        return view('livewire.admin.pickup-agencies-table', [
            'agencies' => $agencies,
            'stats' => $stats,
        ]);
    }
}
