<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\OrderMessage;
use App\Models\Orders;
use App\Events\NewOrderMessage;
use Illuminate\Support\Facades\Auth;
class OrderChat extends Component
{
    public $orderId;
    public $order;
    public $messages = [];
    public $newMessage = '';
    public $userType = 'admin';
    
    public function mount($orderId, $userType = 'admin')
    {
        $this->orderId = $orderId;
        $this->userType = $userType;
        $this->loadOrder();
        $this->loadMessages();
    }
    
    public function loadOrder()
    {
        $this->order = Orders::with('user')->findOrFail($this->orderId);
    }
    
    public function loadMessages()
    {
        $this->messages = OrderMessage::where('order_id', $this->orderId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
        
        // Marcar como leídos
        if ($this->userType === 'admin') {
            OrderMessage::where('order_id', $this->orderId)
                ->where('sender_type', 'customer')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } else {
            OrderMessage::where('order_id', $this->orderId)
                ->whereIn('sender_type', ['admin', 'system'])
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }
        
        $this->dispatch('messagesLoaded');
    }
    
    public function sendMessage()
    {
        $this->validate([
            'newMessage' => 'required|string|max:1000',
        ], [
            'newMessage.required' => 'El mensaje no puede estar vacío',
            'newMessage.max' => 'El mensaje es demasiado largo',
        ]);
        
        // Guardar mensaje
        $message = OrderMessage::create([
            'order_id' => $this->orderId,
            'user_id' => Auth::user()->id ?? 0, 
            'sender_type' => $this->userType === 'admin' ? 'admin' : 'customer',
            'message' => $this->newMessage,
            'is_read' => false,
        ]);
        
        // 🔥 EMITIR EVENTO PARA WEBSOCKET
        broadcast(new NewOrderMessage($message))->toOthers();
        
        $this->newMessage = '';
        $this->loadMessages();
        $this->dispatch('messageSent');
    }
    
    // 🔥 MÉTODO PARA RECIBIR MENSAJES EN TIEMPO REAL
    public function receiveMessage()
    {
        $this->loadMessages();
    }
    
    public function getUnreadCountProperty()
    {
        if ($this->userType === 'admin') {
            return OrderMessage::where('order_id', $this->orderId)
                ->where('sender_type', 'customer')
                ->where('is_read', false)
                ->count();
        } else {
            return OrderMessage::where('order_id', $this->orderId)
                ->whereIn('sender_type', ['admin', 'system'])
                ->where('is_read', false)
                ->count();
        }
    }
    
    public function render()
    {
        return view('livewire.components.order-chat');
    }
}