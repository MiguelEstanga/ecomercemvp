<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Livewire\WithFileUploads;

class UserDetails extends Component
{
    use WithFileUploads;
    
    public $userId;
    public $user;
    public $showModal = false;
    
    // Edición de perfil
    public $editing = false;
    public $name;
    public $email;
    public $phone;
    public $country;
    public $city;
    public $address;
    public $dni;
    public $avatar;
    
    protected $listeners = ['openUserDetailsModal'];
    
    public function openUserDetailsModal($userId)
    {
        $this->userId = $userId;
        $this->loadUser();
        $this->showModal = true;
        $this->editing = false;
    }
    
    public function loadUser()
    {
        $this->user = User::with(['profile', 'orders', 'roles'])->findOrFail($this->userId);
        
        // Cargar datos para edición
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        
        if ($this->user->profile) {
            $this->phone = $this->user->profile->phone;
            $this->country = $this->user->profile->country;
            $this->city = $this->user->profile->city;
            $this->address = $this->user->profile->address;
            $this->dni = $this->user->profile->dni;
        }
    }
    
    public function toggleEdit()
    {
        $this->editing = !$this->editing;
        
        if (!$this->editing) {
            $this->loadUser(); // Recargar datos si se cancela
        }
    }
    
    public function updateUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'dni' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
        ]);
        
        try {
            // Actualizar usuario
            $this->user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);
            
            // Actualizar o crear perfil
            $profileData = [
                'phone' => $this->phone,
                'country' => $this->country,
                'city' => $this->city,
                'address' => $this->address,
                'dni' => $this->dni,
            ];
            
            // Subir avatar si existe
            if ($this->avatar) {
                $path = $this->avatar->store('avatars', 'public');
                $profileData['avatar'] = $path;
            }
            
            if ($this->user->profile) {
                $this->user->profile->update($profileData);
            } else {
                $this->user->profile()->create($profileData);
            }
            
            $this->editing = false;
            $this->loadUser();
            
            session()->flash('success', 'Usuario actualizado exitosamente');
            $this->dispatch('userUpdated');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['userId', 'user', 'editing', 'avatar']);
    }
    
    public function render()
    {
        return view('livewire.admin.user-details');
    }
}