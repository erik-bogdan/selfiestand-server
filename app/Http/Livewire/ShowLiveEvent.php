<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Image;
use App\Models\Project;

class ShowLiveEvent extends Component
{
    public function render()
    {
        $liveEvent = Project::where('is_live_event', 1)->first();
        return view('livewire.show-live-event', [
            'isLiveEvent' => $liveEvent,
            'images' => $liveEvent ? $liveEvent->images()->orderBy('id', 'desc')->get() : [],
        ]);
    }
}
