<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;
use App\services\ProductServices;
use App\services\FileService;
use Illuminate\Support\Facades\Log;

class ProductTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    protected $productServices;
    protected $fileService;
    protected $sortableFields = ['id', 'name', 'price', 'stock', 'created_at', 'is_active'];

    // Escuchar el evento de actualización
    protected $listeners = ['productUpdated' => 'handleProductUpdated'];

    public function boot(ProductServices $productServices, FileService $fileService)
    {
        $this->productServices = $productServices;
        $this->fileService = $fileService;
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

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

    public function handleProductUpdated()
    {
        // Refresh la tabla cuando se actualice un producto
        $this->render();
    }

    public function deleteProduct($productId)
    {

        $this->dispatch('startLoading', message: 'Eliminando producto...');

        try {
            $result = $this->productServices->delete((int) $productId);

            if ($result['error'] !== null) {
                $errorMessage = is_string($result['error']) ? $result['error'] : 'Error al eliminar el producto.';
                session()->flash('error', $errorMessage);
            } else {
                session()->flash('success', 'Producto eliminado exitosamente.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error inesperado al eliminar el producto.');
            Log::error('Error inesperado al eliminar producto: ' . $e->getMessage());
        }finally{
            $this->dispatch('stopLoading');
        }

        
       
    }

    public function activarProducto($productId)
    {
        $this->dispatch('startLoading');
        try {
            $result = $this->productServices->activarProducto((int) $productId);
            // Verifica si el servicio devolvió un error
            if ($result['error'] !== null) {
                $errorMessage = is_string($result['error']) ? $result['error'] : 'Error al activar el producto.';
                session()->flash('error', $errorMessage);
                return;
            }
            session()->flash('success', 'Producto activado exitosamente.');
            return;
        } catch (\Exception $e) {
            // Este catch solo atraparía errores muy inesperados (ej. si el servicio falla por completo)
            // La mayoría de los errores de negocio deben manejarse dentro del servicio.
            session()->flash('error', 'Ocurrió un error inesperado al activar el producto.');
            return;
        } finally {
            $this->dispatch('stopLoading');
            $this->render();
        }
    }



    public function render()
    {
        $products = Product::query()
            ->with('product_imagens')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.product-table', [
            'products' => $products,
        ]);
    }
}
