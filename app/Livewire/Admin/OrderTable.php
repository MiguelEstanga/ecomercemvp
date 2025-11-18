<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\OrderServices;
use App\Models\Orders as Order;
use App\services\FileService;
use App\Models\PaymentMethods;

class OrderTable extends Component
{
    use WithPagination;

    //services

    protected $fileService;
    // Propiedades para búsqueda y filtros
    public $search = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $paymentMethodFilter = '';

    // Propiedades para ordenamiento
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Paginación
    public $perPage = 10;

    // Campos ordenables
    protected $sortableFields = [
        'id',
        'order_number',
        'total_amount',
        'status',
        'created_at',
        'updated_at'
    ];

    // Listeners
    protected $listeners = [
        'orderUpdated' => '$refresh',
        'orderDeleted' => '$refresh'
    ];
    public function boot(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    // Resetear paginación al buscar
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPaymentMethodFilter()
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
        $this->reset(['search', 'statusFilter', 'dateFrom', 'dateTo', 'paymentMethodFilter']);
        $this->resetPage();
    }

    // Cambiar estado de orden
    public function updateOrderStatus($orderId, $newStatus)
    {
        try {
            $order = Order::findOrFail($orderId);
            $order->update(['status' => $newStatus]);

            session()->flash('success', 'Estado de orden actualizado exitosamente');
            $this->dispatch('orderUpdated');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el estado: ' . $e->getMessage());
        }
    }

    // Ver detalles de orden
    public function viewOrder($orderId)
    {
        $this->dispatch('openOrderDetailsModal', orderId: $orderId);
    }

    // Eliminar orden
    public function deleteOrder($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            // Eliminar imágenes si existen
            if ($order->imagen_documento) {
                $this->fileService->delete($order->imagen_documento);
            }
            if ($order->imagen_comprobante) {
                $this->fileService->delete($order->imagen_comprobante);
            }

            
            $order->delete();

            session()->flash('success', 'Orden eliminada exitosamente');
            $this->dispatch('orderDeleted');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la orden: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Order::with(['user',  'pickupAgency', 'items.product']);

        // Búsqueda
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                    ->orWhere('reference_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($userQuery) {
                        $userQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Filtro por estado
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Filtro por método de pago
        if ($this->paymentMethodFilter) {
            $query->where('payment_method_id', $this->paymentMethodFilter);
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

        $orders = $query->paginate($this->perPage);

        // Estadísticas
        $statsQuery = Order::query();
        if ($this->dateFrom) {
            $statsQuery->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $statsQuery->whereDate('created_at', '<=', $this->dateTo);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'pending' => (clone $statsQuery)->where('status', 'pending')->count(),
            'completed' => (clone $statsQuery)->where('status', 'completed')->count(),
            'total_sales' => (clone $statsQuery)->where('status', 'completed')->sum('total_amount'),
        ];

        // Obtener métodos de pago para el filtro
        $paymentMethods =  PaymentMethods::all();

        return view('livewire.admin.order-table', [
            'orders' => $orders,
            'stats' => $stats,
            'paymentMethods' => $paymentMethods,
        ]);
    }
}
