<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;
use App\Models\Image as ImageModel;

class ConvertImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'selfiestand:convert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Képek konvertálása';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $images = ImageModel::where('manipulated_path', null)->get();
        $counter = 1;
        $all = count($images);
        foreach ($images as $image) {
            $pathBase = explode('/', $image->image_path);
            $trimmedBasePath = $pathBase[0] . '/' . $pathBase[1] . '/processed';

            if (!\File::isDirectory(public_path('storage/' . $pathBase[0] . '/' . $pathBase[1] . '/processed'))) {
                \File::makeDirectory(public_path('storage/' . $pathBase[0] . '/' . $pathBase[1] . '/processed'), 0777, true, true);
            }

            $img = \Storage::disk('public')->get($image->image_path);
            $watermark = \Storage::disk('public')->get('images/vizjel.png');
            $watermarkNew = Image::make($watermark)->resize(170, null, function ($constraint) {
                $constraint->aspectRatio();
            })->opacity(80);
            
            $manipulatedPublicPath = public_path('storage/' . $trimmedBasePath . '/' . $pathBase[1] . '_'. $counter . '.jpg');
            $manipulatedPath =  $trimmedBasePath . '/' . $pathBase[1] . '_'. $counter . '.jpg';

            $imgNew = Image::make($img)->resize(1000, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->contrast(5)
            ->colorize(0, -3, 0)
            ->flip('h')
            ->insert($watermarkNew, 'bottom-right', 30, 30)
            ->save($manipulatedPublicPath)
            ;

            $image->manipulated_path = $manipulatedPath;
            $image->save();
            $this->info('Sikeres feldolgozás: ' . $counter . '/' . $all);
            $counter ++;
        }

        $this->info('A feldolgozás elkészült a naphoz!');
    }
}
