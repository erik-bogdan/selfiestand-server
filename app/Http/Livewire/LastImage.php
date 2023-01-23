<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Project;

class LastImage extends Component
{
    public function render()
    {
        $liveEvent = Project::where('is_live_event', 1)->first();
        return view('livewire.last-image', [
            'isLiveEvent' => $liveEvent,
            'image' => $liveEvent ? $liveEvent->images()->orderBy('id', 'desc')->first() : null,
        ]);
    }
}
