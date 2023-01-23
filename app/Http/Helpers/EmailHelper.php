<?php
namespace App\Http\Helpers;

use Mail;
use Intervention\Image\Facades\Image;

class EmailHelper
{
    public static function sendImage($image, $decodedMails)
    {
        $data["title"] = "SelfieStand fényképed";
        $data["body"] = "";

        $imageUrl = \Storage::disk('public')->get($image->image_path);
        $imagePath = \Storage::disk('public')->path($image->image_path);

        $image = Image::make($imagePath);

        Mail::to($decodedMails)
        ->send(new \App\Mail\ImageMail($imageUrl, $image->basename));
    }
}
