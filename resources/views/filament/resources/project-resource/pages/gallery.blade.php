<x-filament::page>
<div style="display: grid; grid-template-columns: 1fr 1fr 1fr; row-gap: 2em; column-gap: 2em">
@foreach ($record->images as $image)
    <div class="relative image">
      <div class="image-menu absolute top-0 left-0 w-full h-full">
        <div class="item-option mr-3">
          <a href="#" onclick="Livewire.emit('modal:open', 'send-email', {{$image->id}})"><img src="{{asset('storage/images/icons/new-message.svg')}}" /></a>
        </div>
        <div class="item-option mr-3">
          <img src="{{asset('storage/images/icons/print.svg')}}" />
        </div>
        <div class="item-option">
          <a href="{{route('downloadfile',$image->id)}}" target="_blank"><img src="{{asset('storage/images/icons/save.svg')}}" /></a>
        </div>
      </div>
      <img class="selfie-image" src="{{asset('storage/' . $image->manipulated_path)}}" style="width: 100%"/>
    </div>
@endforeach
</div>
</x-filament::page>

<x-tall-interactive::modal
  id="send-email"
  :form="\App\Forms\EmailForm::class"
  :imageId="135"
 />
