<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Orders;

class OrderDetails extends Component
{
    public $orderId;
    public $order;
    public $showModal = false;
    
    // Chat
    public $messages = [];
    public $newMessage = '';
    
    protected $listeners = ['openOrderDetailsModal'];
    
    public function openOrderDetailsModal($orderId)
    {
        $this->orderId = $orderId;
        $this->loadOrder();
        $this->loadMessages();
        $this->showModal = true;
    }
    
    public function loadOrder()
    {
        $this->order = Orders::with([
            'user',
            'items.product.product_imagens',
            'paymentMethod',
            'pickupAgency'
        ])->findOrFail($this->orderId);
    }
    
    public function loadMessages()
    {
        // Por ahora mensajes de ejemplo
        // Cuando implementes la BD, cargarías desde OrderMessages o similar
        $this->messages = [
            [
                'id' => 1,
                'sender' => 'support',
                'message' => '¡Hola! ¿En qué puedo ayudarte con tu orden?',
                'created_at' => now()->subMinutes(5),
            ],
        ];
    }
    
    public function sendMessage()
    {
        if (trim($this->newMessage) === '') {
            return;
        }
        
        // Agregar mensaje del admin
        $this->messages[] = [
            'id' => count($this->messages) + 1,
            'sender' => 'admin',
            'message' => $this->newMessage,
            'created_at' => now(),
        ];
        
        // Aquí guardarías en la BD
        // OrderMessage::create([...])
        
        $this->newMessage = '';
        
        // Simular respuesta automática (quitar en producción)
        $this->dispatch('messageSent');
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['orderId', 'order', 'messages', 'newMessage']);
    }
    
    public function render()
    {
        return view('livewire.admin.order-details');
    }
}