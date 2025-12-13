<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Contato;

class ContatoTable extends Component
{
    use WithPagination;

    // Propiedades de búsqueda y filtros
    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;

    // Propiedades del formulario
    public $showCreateModal = false;
    public $showEditModal = false;
    public $contatoId;
    public $telefono_contacto;
    public $telefono_banco;
    public $codigo_banco;
    public $nombre_banco;
    public $cuenta_banco;
    public $is_active = true;

    // Ordenamiento
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $listeners = [
        'refreshTable' => '$refresh',
    ];

    // Reglas de validación
    protected function rules()
    {
        return [
            'telefono_contacto' => 'nullable|string|max:20',
            'telefono_banco' => 'nullable|string|max:20',
            'codigo_banco' => 'nullable|string|max:10',
            'nombre_banco' => 'required|string|max:255',
            'cuenta_banco' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'nombre_banco.required' => 'El nombre del banco es obligatorio',
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

    // Crear contacto
    public function createContato()
    {
        $this->validate();

        try {
            Contato::create([
                'telefono_contacto' => $this->telefono_contacto,
                'telefono_banco' => $this->telefono_banco,
                'codigo_banco' => $this->codigo_banco,
                'nombre_banco' => $this->nombre_banco,
                'cuenta_banco' => $this->cuenta_banco,
                'is_active' => $this->is_active,
            ]);

            session()->flash('success', 'Contacto bancario creado exitosamente');
            $this->closeCreateModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el contacto: ' . $e->getMessage());
        }
    }

    // Abrir modal de editar
    public function openEditModal($id)
    {
        $contato = Contato::findOrFail($id);

        $this->contatoId = $contato->id;
        $this->telefono_contacto = $contato->telefono_contacto;
        $this->telefono_banco = $contato->telefono_banco;
        $this->codigo_banco = $contato->codigo_banco;
        $this->nombre_banco = $contato->nombre_banco;
        $this->cuenta_banco = $contato->cuenta_banco;
        $this->is_active = $contato->is_active;

        $this->showEditModal = true;
    }

    // Cerrar modal de editar
    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    // Actualizar contacto
    public function updateContato()
    {
        $this->validate();

        $this->dispatch('startLoading');
        try {
            $contato = Contato::findOrFail($this->contatoId);

            $contato->update([
                'telefono_contacto' => $this->telefono_contacto,
                'telefono_banco' => $this->telefono_banco,
                'codigo_banco' => $this->codigo_banco,
                'nombre_banco' => $this->nombre_banco,
                'cuenta_banco' => $this->cuenta_banco,
                'is_active' => $this->is_active,
            ]);

            session()->flash('success', 'Contacto bancario actualizado exitosamente');
            $this->closeEditModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el contacto: ' . $e->getMessage());
        } finally {
            $this->dispatch('stopLoading');
        }
    }

    // Cambiar estado
    public function toggleStatus($id)
    {
        $this->dispatch('startLoading');
        try {
            $contato = Contato::findOrFail($id);
            $contato->is_active = !$contato->is_active;
            $contato->save();

            $status = $contato->is_active ? 'activado' : 'desactivado';
            session()->flash('success', "Contacto {$status} exitosamente");
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el estado del contacto');
        }

        $this->dispatch('stopLoading');
    }

    // Eliminar contacto
    public function deleteContato($id)
    {
        $this->dispatch('startLoading');
        try {
            $contato = Contato::findOrFail($id);
            $contato->is_active = false;
            $contato->save();

            session()->flash('success', 'Contacto bancario eliminado exitosamente');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el contacto');
        }
        $this->dispatch('stopLoading');
    }

    // Resetear formulario
    private function resetForm()
    {
        $this->reset([
            'contatoId',
            'telefono_contacto',
            'telefono_banco',
            'codigo_banco',
            'nombre_banco',
            'cuenta_banco',
            'is_active',
        ]);
        $this->is_active = true;
    }

    public function render()
    {
        $query = Contato::query();

        // Búsqueda
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre_banco', 'like', '%' . $this->search . '%')
                    ->orWhere('codigo_banco', 'like', '%' . $this->search . '%')
                    ->orWhere('cuenta_banco', 'like', '%' . $this->search . '%')
                    ->orWhere('telefono_contacto', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro por estado
        if ($this->statusFilter !== '') {
            $query->where('is_active', $this->statusFilter);
        }

        // Ordenamiento
        $query->orderBy($this->sortField, $this->sortDirection);

        // Paginación
        $contatos = $query->paginate($this->perPage);

        // Estadísticas
        $stats = [
            'total' => Contato::count(),
            'active' => Contato::where('is_active', true)->count(),
            'inactive' => Contato::where('is_active', false)->count(),
        ];

        return view('livewire.admin.contato-table', [
            'contatos' => $contatos,
            'stats' => $stats,
        ]);
    }
}
