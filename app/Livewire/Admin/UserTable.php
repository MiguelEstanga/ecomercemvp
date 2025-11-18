<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class UserTable extends Component
{
    use WithPagination;

    // Propiedades para búsqueda y filtros
    public $search = '';
    public $statusFilter = '';
    public $roleFilter = '';
    public $dateFrom = '';
    public $dateTo = '';

    // Propiedades para ordenamiento
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Paginación
    public $perPage = 10;

    // Campos ordenables
    protected $sortableFields = [
        'id',
        'name',
        'email',
        'created_at',
    ];

    // Listeners
    protected $listeners = [
        'userUpdated' => '$refresh',
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

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    // Método para ordenar
    public function sortBy($field)
    {
        if (!in_array($field, $this->sortableFields)) {
            return;
        }

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
        $this->reset(['search', 'statusFilter', 'roleFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    // Activar/Desactivar usuario (Borrado lógico)
    public function toggleUserStatus($userId)
    {
        $this->dispatch('startLoading');
        try {
            usleep(500000);
            $user = User::findOrFail($userId);

            // Cambiar estado activo
            $user->active = !$user->active;
            $user->save();

            $status = $user->active ? 'activado' : 'desactivado';
            session()->flash('success', "Usuario {$status} exitosamente");

            $this->dispatch('userUpdated');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el estado del usuario');
        } finally {
            $this->dispatch('stopLoading');
        }
    }

    // Ver detalles del usuario
    public function viewUser($userId)
    {
        $this->dispatch('openUserDetailsModal', userId: $userId);
    }

    // Ver órdenes del usuario
    public function viewUserOrders($userId)
    {
        $this->dispatch('openUserOrdersModal', userId: $userId);
    }

    // Eliminar usuario físicamente (solo para admin)
    public function deleteUser($userId)
    {
        try {
            $user = User::findOrFail($userId);

            // No permitir eliminar admin principal o usuario actual
            if ($user->id === 1 || $user->id === Auth::user()->id) {
                session()->flash('error', 'No puedes eliminar este usuario');
                return;
            }

            $user->delete();

            session()->flash('success', 'Usuario eliminado exitosamente');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el usuario');
        }
    }

    public function render()
    {
        $this->dispatch('startLoading');
        try {
            // Query principal con eager loading
            $query = User::with(['profile', 'orders', 'roles']);

            // Búsqueda
            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhereHas('profile', function ($profileQuery) {
                            $profileQuery->where('phone', 'like', '%' . $this->search . '%')
                                ->orWhere('dni', 'like', '%' . $this->search . '%');
                        });
                });
            }

            // Filtro por estado activo
            if ($this->statusFilter !== '') {
                $query->where('active', $this->statusFilter);
            }

            // Filtro por rol
            if ($this->roleFilter) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->roleFilter);
                });
            }

            // Filtro por fecha
            if ($this->dateFrom) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            }

            if ($this->dateTo) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            }

            // Ordenamiento
            $query->orderBy($this->sortField, $this->sortDirection);

            // Paginación
            $users = $query->paginate($this->perPage);

            // Estadísticas
            $stats = [
                'total' => User::count(),
                'active' => User::where('active', true)->count(),
                'inactive' => User::where('active', false)->count(),
                'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
            ];

            // Obtener roles disponibles
            $roles = \Spatie\Permission\Models\Role::all();

            return view('livewire.admin.user-table', [
                'users' => $users,
                'stats' => $stats,
                'roles' => $roles,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener la tabla de usuarios: ' . $e->getMessage());
            return redirect()->back();
        } finally {
            $this->dispatch('stopLoading');
        }
    }
}
