<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\Page;

class GalleryProject extends Page
{
    protected static string $resource = ProjectResource::class;

    protected static string $view = 'filament.resources.project-resource.pages.gallery';
    protected static ?string $title = 'Galéria';

    public static function getLabel(): string
    {
        return __('project.gallery.title');
    }

    public static function getPluralLabel(): string
    {
        return __('project.gallery.title');
    }

    public function downloadFile($id)
    {
        $file = \App\Models\Image::where('id', $id)->first();
        // return $file;
        $myfile = public_path($file->manipulated_path);
        return response()->download($myfile);
    }
    
    public $record;
 
    public function mount($record)
    {
        $this->record = \App\Models\Project::find($record);
    }
}
