<?php

namespace App\Livewire\Components;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Loader extends Component
{
    public $isLoading = false;
    protected $listeners = ['startLoading', 'stopLoading'];

    public function startLoading()
    {
        $this->isLoading = true;
        Log::info( $this->isLoading);
    }

    public function stopLoading()
    {
        $this->isLoading = false;
        Log::info(  $this->isLoading);
    }
    public function render()
    {
        return view('livewire.components.loader');
    }
}
