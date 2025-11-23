<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class Loader extends Component
{
    public $isLoading = false;

    #[On('startLoading')]
    public function startLoading()
    {
        $this->isLoading = true;
    }

    #[On('stopLoading')]
    public function stopLoading()
    {
        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.components.loader', [
            'isLoading' => $this->isLoading
        ]);
    }
}
