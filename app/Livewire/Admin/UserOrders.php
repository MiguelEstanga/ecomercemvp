<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Orders;

class UserOrders extends Component
{
    use WithPagination;
    
    public $userId;
    public $user;
    public $showModal = false;
    
    // Filtros
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 10;
    
    protected $listeners = ['openUserOrdersModal'];
    
    public function openUserOrdersModal($userId)
    {
        $this->userId = $userId;
        $this->loadUser();
        $this->showModal = true;
        $this->resetPage();
    }
    
    public function loadUser()
    {
        $this->user = User::findOrFail($this->userId);
    }
    
    public function updatingStatusFilter()
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
    
    public function clearFilters()
    {
        $this->reset(['statusFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }
    
    public function viewOrder($orderId)
    {
        $this->dispatch('openOrderDetailsModal', orderId: $orderId);
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['userId', 'user', 'statusFilter', 'dateFrom', 'dateTo']);
    }
    
    public function render()
    {
        $query = Orders::where('user_id', $this->userId)
            ->with(['items.product', 'paymentMethod']);
        
        // Filtros
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }
        
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate($this->perPage);
        
        // Estadísticas
        $stats = [
            'total' => Orders::where('user_id', $this->userId)->count(),
            'completed' => Orders::where('user_id', $this->userId)->where('status', 'completed')->count(),
            'pending' => Orders::where('user_id', $this->userId)->where('status', 'pending')->count(),
            'total_amount' => Orders::where('user_id', $this->userId)->where('status', 'completed')->sum('total_amount'),
        ];
        
        return view('livewire.admin.user-orders', [
            'orders' => $orders,
            'stats' => $stats,
        ]);
    }
}