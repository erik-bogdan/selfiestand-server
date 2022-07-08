<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;
use App\Models\Image as ImageModel;

class GenerateThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'selfiestand:generate-thumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Thumbnail generation for images';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $images = ImageModel::where([
            ['thumbnail_path', null],
            ['image_path', '!=', null]
        ])->get();
        $counter = 1;
        $all = count($images);
        foreach ($images as $image) {
            $pathBase = explode('/', $image->image_path);
            $trimmedBasePath = $pathBase[0] . '/' . $pathBase[1] . '/processed';

            if (!\File::isDirectory(public_path('storage/' . $pathBase[0] . '/' . $pathBase[1] . '/processed/thumbnail'))) {
                \File::makeDirectory(public_path('storage/' . $pathBase[0] . '/' . $pathBase[1] . '/processed/thumbnail'), 0777, true, true);
            }

            $manipulatedPublicPath = public_path('storage/' . $trimmedBasePath . '/' . $pathBase[1] . '_'. $counter . '.jpg');
            $manipulatedPublicPathThumbnail = public_path('storage/' . $trimmedBasePath . '/thumbnail/' . $pathBase[1] . '_'. $counter . '.jpg');
            $manipulatedPath =  $trimmedBasePath . '/thumbnail/' .  $pathBase[1] . '_'. $counter . '.jpg';


            $img = \Storage::disk('public')->get($image->image_path);

            $imgNew = Image::make($img)->resize(384, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->contrast(5)
            ->colorize(0, -3, 0)
            ->flip('h')
            ->save($manipulatedPublicPathThumbnail)
            ;
  
            $image->thumbnail_path = $manipulatedPath;
            $image->save();

            $this->info('Sikeres feldolgozás: ' . $counter . '/' . $all);
            $counter ++;
        }

        $this->info('A feldolgozás elkészült a naphoz!');
    }
}
