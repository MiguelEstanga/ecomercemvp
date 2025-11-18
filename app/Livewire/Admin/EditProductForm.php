<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
 
use App\Services\FileService;
use App\services\ProductServices;
use Illuminate\Support\Facades\Log;
class EditProductForm extends Component
{
    use WithFileUploads;
    //servicios
    protected $productServices;
    protected $fileService;
    // Control del modal
    public $showModal = false;

    // ID del producto
    public $productId;
    public $product;

    // Campos del formulario
    public $name;
    public $slug;
    public $description;
    public $price;
    public $stock;
    public $is_active = false;
    public $imagen;

    // Escuchar eventos
    protected $listeners = ['openEditModal'];

    
    // Reglas de validación
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug,' . $this->productId,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'imagen' => 'nullable|image|max:2048', // 2MB máximo
        ];
    }

    // Mensajes de validación personalizados
    protected $messages = [
        'name.required' => 'El nombre es obligatorio',
        'slug.required' => 'El slug es obligatorio',
        'slug.unique' => 'Este slug ya existe',
        'price.required' => 'El precio es obligatorio',
        'price.numeric' => 'El precio debe ser un número',
        'stock.required' => 'El stock es obligatorio',
        'stock.integer' => 'El stock debe ser un número entero',
        'imagen.image' => 'El archivo debe ser una imagen',
        'imagen.max' => 'La imagen no puede pesar más de 2MB',
    ];
    public function boot(ProductServices $productServices)
    {
        $this->productServices = $productServices;
    }
    // Abrir modal y cargar producto
    public function openEditModal($productId)
    {
        $this->resetValidation();
        $this->productId = $productId;
        $this->loadProduct();
        $this->showModal = true;
    }



    // Cargar datos del producto
    public function loadProduct(
        
        )
    {
        $this->product = $this->productServices->findId($this->productId);

        // Llenar los campos del formulario
        $this->name = $this->product->name;
        $this->slug = $this->product->slug;
        $this->description = $this->product->description;
        $this->price = $this->product->price;
        $this->stock = $this->product->stock;
        $this->is_active = (bool) $this->product->is_active;
    }

    // Actualizar producto
    public function updateProduct(FileService $fileService , ProductServices $productServices)
    {
        $this->validate();

        try {
            // Preparar datos
            $data = [
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
                'is_active' => $this->is_active ? 1 : 0,
            ];

            // Manejar la nueva imagen si existe
            if ($this->imagen) {
                // Eliminar imagen anterior si existe
                if ($this->product->product_imagens->count() > 0) {
                    $oldPath = $this->product->product_imagens[0]->path;
                    $fileService->delete($oldPath);
                    $this->product->product_imagens[0]->delete();
                }

                // Subir nueva imagen
                $path = $this->imagen->store('products', 'public');

                // Guardar en la tabla de imágenes
                $this->product->product_imagens()->create([
                    'path' => $path,
                    'is_main' => true,
                ]);
            }

            // Actualizar el producto
            $productServices->update($this->productId, $data);

            // Dispatch evento para actualizar la tabla
            $this->dispatch('productUpdated');

            // Cerrar modal
            $this->closeModal();

            // Mensaje de éxito
            session()->flash('success', 'Producto actualizado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar el producto: ' . $e->getMessage());
            session()->flash('error', 'Error al actualizar el producto: ' . $e->getMessage());
        }
    }

    // Eliminar imagen actual
    public function deleteImage(FileService $fileService)
    {
        try {
            if ($this->product->product_imagens->count() > 0) {
                $imagePath = $this->product->product_imagens[0]->path;
                $fileService->delete($imagePath);
                $this->product->product_imagens[0]->delete();

                // Recargar producto
                $this->loadProduct();

                session()->flash('success', 'Imagen eliminada exitosamente');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la imagen');
        }
    }

    // Cerrar modal y resetear
    public function closeModal()
    {
        $this->showModal = false;
        $this->reset([
            'productId',
            'product',
            'name',
            'slug',
            'description',
            'price',
            'stock',
            'is_active',
            'imagen'
        ]);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.edit-product-form');
    }
}
