<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Images extends Component
{
    public $images = [];

    public function render()
    {
        return view('livewire.images', [
            'images' => $this->images
        ]);
    }
}
