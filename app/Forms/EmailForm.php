<?php
 
namespace App\Forms;

use RalphJSmit\Tall\Interactive\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Illuminate\Support\Collection;
use Filament\Forms\Components\TagsInput;

class EmailForm extends Form
{
    public $imageId = '';
    public array $prefilledValues = [];

    public function getFormSchema(Component $livewire): array
    {
        return [
          TagsInput::make('email')
            ->label('Írd be az email-címet')
            ->placeholder('john@example.com, john2@example.com')
            ->helperText('Vesszővel elválasztva tudsz többet is felsorolni!')
            ->required(),
     
        ];
    }
 
    public function submit(Collection $state): void
    {
        //User::create($state->all());
        $values = $state->all();
        $image = \App\Models\Image::where('id', $this->imageId)->first();

        \App\Http\Helpers\EmailHelper::sendImage($image, $state['email']);
        toast()
            ->success('Thanks for submitting the form! (Your data isn\'t stored anywhere.')
            ->push();
    }
 


    public function onOpen(array $eventParams, self $formClass): void
    {
        $formClass->imageId = $eventParams[0];
    }

    public function getErrorMessages(): array
    {
        return [
            'email.required' => 'Adj meg legalább 1 email címet',
        ];
    }

    public function fill(): array
    {
        return [
            'imageId' => $this->imageId,
         ];
    }
}
