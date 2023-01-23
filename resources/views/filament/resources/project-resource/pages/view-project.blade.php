<x-filament::page>
  <div class="flex">
    <div class="flex-1">
      <div class="text-2xl font-bold mb-2">
        Project neve:
      </div>
      <div class="text-xl font-bold">
        {{$record->project_title}}
      </div>
    </div>
    <div class="flex-1">
    <div class="text-2xl font-bold mb-2">
        Project dátuma:
      </div>
      <div class="text-xl font-bold">
        {{$record->project_date}}
      </div>
    </div>
  </div>
  <div class="flex">
    <div class="flex-1">
      <div class="text-2xl font-bold mb-2">
        QR kód:
      </div>
      <div class="text-xl font-bold">
      {!! QrCode::size(200)->generate($record->uuid) !!}
      </div>
    </div>
    
  </div>
</x-filament::page>
