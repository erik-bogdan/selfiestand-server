<?php
 
namespace App\Forms;

use RalphJSmit\Tall\Interactive\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class EmailForm extends Form
{
    public int $imageId;
  
    public function getFormSchema(Component $livewire): array
    {
        return [
          TextInput::make('email')
            ->label('Enter your email')
            ->placeholder('john@example.com')
            ->required(),
        Placeholder::make('open_child_modal')
            ->disableLabel()
            ->content(
                new HtmlString('Click <button onclick="Livewire.emit(\'modal:open\', \'create-user-child\')" type="button" class="text-primary-500">here</button> to open a child modal🤩')
            ),
        ];
    }
 
    public function submit(Collection $state): void
    {
        //User::create($state->all());
     
        toast()
            ->success('Thanks for submitting the form! (Your data isn\'t stored anywhere.')
            ->push();
    }
 
    public function mount(array $params): void
    {
        $this->imageId = $params['imageId'];
    }

    public function getErrorMessages(): array
    {
        return [
            'email.required' => 'Please fill in your e-email',
        ];
    }

    public function fill(): array
    {
        $image = \App\Models\Image::where('id', $this->imageId)->first();
      
        return [
          'email' => $image->manipulated_path,
       ];
    }
 
    /** Only applicable for Modals and SlideOvers */
    public function onOpen(): void
    {
        //
    }
}
