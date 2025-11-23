<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product  ; // Asegúrate de que 'products' sea tu modelo
use Livewire\Attributes\On; // Necesario para escuchar eventos JS
use Livewire\WithFileUploads;
class ProductForm extends Component
{
    use WithFileUploads;
    // 1. Estado del Modal
    public $showModal = false; // Controla si el modal está abierto o cerrado

    // 2. Propiedades del Formulario
    public $name = '';
    public $slug = '';
    public $description = '';
    public $price = 0.00;
    public $stock = 0;
    public $is_active = true;


    public $imagen = [];



    // 3. Reglas de Validación
    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|unique:products,slug|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0.01',
        'stock' => 'required|integer|min:0',
        'is_active' => 'boolean',
        'imagen.*' => 'nullable|image|max:5120'
    ];

    // 4. Métodos de Control del Modal

    // El decorador @On() hace que este método se ejecute cuando se reciba el evento 'open-product-modal'
    #[On('open-product-modal')]
    public function openModal()
    {
        // Limpiamos la validación y el estado anterior del formulario
        $this->resetValidation();
        $this->reset(['name', 'slug', 'description', 'price', 'stock', 'is_active', 'imagen']);

        // Abrimos el modal
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    // 5. Método de Guardado
    public function saveProduct()
    {
        $this->validate(); // Ejecuta las reglas de validación

        Product::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'is_active' => $this->is_active,
        ]);

        // Cierra el modal después de guardar
        $this->closeModal();

         
        $this->dispatch('product-saved');

      
    }

    public function render()
    {
        return view('livewire.admin.product-form');
    }
}
