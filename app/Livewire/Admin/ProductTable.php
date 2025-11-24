<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Services\ProductServices;

class ProductTable extends Component
{
    use WithPagination, WithFileUploads;

    protected $productService;

    // Propiedades de búsqueda y filtros
    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $stockFilter = '';
    public $perPage = 10;

    // Propiedades del formulario
    public $showCreateModal = false;
    public $showEditModal = false;
    public $productId;
    public $name;
    public $description;
    public $price;
    public $stock;
    public $category_id;
    public $is_active = true;
    public $sku;

    // Imágenes
    public $images = [];
    public $existingImages = [];
    public $imagesToDelete = [];

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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
             
            'images.*' => 'nullable|image|max:2048',
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre es obligatorio',
        'price.required' => 'El precio es obligatorio',
        'price.numeric' => 'El precio debe ser un número',
        'stock.required' => 'El stock es obligatorio',
        'category_id.required' => 'Debes seleccionar una categoría',
        'images.*.image' => 'Los archivos deben ser imágenes',
        'images.*.max' => 'Las imágenes no deben pesar más de 2MB',
    ];

    // Boot - Inyección de dependencias
    public function boot(ProductServices $productService)
    {
        $this->productService = $productService;
    }

    // Resetear paginación al buscar
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingStockFilter()
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
        $this->reset(['search', 'categoryFilter', 'statusFilter', 'stockFilter']);
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

    // Crear producto
    public function createProduct()
    {
        $this->dispatch("startLoading");
        $this->validate();
        try {


            $result = $this->productService->createProduct(
                [
                    'name' => $this->name,
                    'description' => $this->description,
                    'price' => $this->price,
                    'stock' => $this->stock,
                    'category_id' => $this->category_id,
                    'is_active' => $this->is_active,
                    'sku' => $this->sku,
                ],
                $this->images
            );

            if ($result['error']) {
                session()->flash('error', $result['error']);
            } else {
                session()->flash('success', 'Producto creado exitosamente');
                $this->closeCreateModal();
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        } finally {
            $this->dispatch("stopLoading");
        }
    }

    // Abrir modal de editar
    public function openEditModal($id)
    {
        $this->dispatch("startLoading");
        try {
            $product = $this->productService->findId($id);

            if (!$product) {
                session()->flash('error', 'Producto no encontrado');
                return;
            }

            $this->productId = $product->id;
            $this->name = $product->name;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->stock = $product->stock;
            $this->category_id = $product->category_id;
            $this->is_active = $product->is_active;
            $this->sku = $product->sku;
            $this->existingImages = $product->product_imagens->toArray();

            $this->showEditModal = true;
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return;
        } finally {
            $this->dispatch("stopLoading");
        }
    }

    // Cerrar modal de editar
    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    // Actualizar producto
    public function updateProduct()
    {
        $this->dispatch("startLoading");
        $this->validate();

        try {
            $result = $this->productService->updateProduct(
                $this->productId,
                [
                    'name' => $this->name,
                    'description' => $this->description,
                    'price' => $this->price,
                    'stock' => $this->stock,
                    'category_id' => $this->category_id,
                    'is_active' => $this->is_active,
                    'sku' => $this->sku,
                ],
                $this->images,
                $this->imagesToDelete
            );

            if ($result['error']) {
                session()->flash('error', $result['error']);
            } else {
                session()->flash('success', 'Producto actualizado exitosamente');
                $this->closeEditModal();
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        } finally {
            $this->dispatch("stopLoading");
        }
    }

    // Marcar imagen para eliminar
    public function markImageForDeletion($imageId)
    {
        if (!in_array($imageId, $this->imagesToDelete)) {
            $this->imagesToDelete[] = $imageId;
        }

        $this->existingImages = array_filter($this->existingImages, function ($img) use ($imageId) {
            return $img['id'] !== $imageId;
        });
    }

    // Cambiar estado
    public function toggleStatus($id)
    {
        $this->dispatch("startLoading");
        try {
            $result = $this->productService->toggleStatus($id);

            if ($result['error']) {
                session()->flash('error', $result['error']);
            } else {
                $status = $result['status'];
                session()->flash('success', "Producto {$status} exitosamente");
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        } finally {

            $this->dispatch("stopLoading");
        }
    }

    // Eliminar producto
    public function deleteProduct($id)
    {
        $result = $this->productService->deleteProduct($id);

        if ($result['error']) {
            session()->flash('error', $result['error']);
        } else {
            session()->flash('success', 'Producto eliminado exitosamente');
        }
    }

    // Resetear formulario
    private function resetForm()
    {
        $this->reset([
            'productId',
            'name',
            'description',
            'price',
            'stock',
            'category_id',
            'is_active',
            'sku',
            'images',
            'existingImages',
            'imagesToDelete'
        ]);
        $this->is_active = true;
    }

    public function render()
    {
        // Preparar filtros
        $filters = [
            'search' => $this->search,
            'category_id' => $this->categoryFilter,
            'is_active' => $this->statusFilter,
            'stock_filter' => $this->stockFilter,
            'sort_field' => $this->sortField,
            'sort_direction' => $this->sortDirection,
            'per_page' => $this->perPage,
        ];

        // Obtener productos con filtros
        $products = $this->productService->getProductsWithFilters($filters);

        // Obtener categorías
        $categories = Category::all();

        // Obtener estadísticas
        $stats = $this->productService->getStatistics();

        return view('livewire.admin.product-table', [
            'products' => $products,
            'categories' => $categories,
            'stats' => $stats,
        ]);
    }
}
