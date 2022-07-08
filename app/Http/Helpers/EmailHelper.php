<?php
namespace App\Http\Helpers;

use Mail;

class EmailHelper
{
    public static function sendImage($image, $decodedMails)
    {
        $data["title"] = "SelfieStand fényképed";
        $data["body"] = "";
      
        $files = [];
        $imagePath = \Storage::disk('public')->path($image->manipulated_path);
        $mimeType = \Storage::disk('public')->mimeType($image->manipulated_path);
        $files[] = [
                'pathToFile' => $imagePath,
                'as' => 'selfiestand.jpg',
                'mime' => $mimeType,
            ];

        $data['img_url'] = $imagePath;
        Mail::send('emails.imageMail', $data, function ($message) use ($data, $files, $decodedMails) {
            $message->to($decodedMails)
                  ->subject($data["title"]);

            foreach ($files as $file) {
                $message->attach($file['pathToFile'], [
                  'as' => $file['as'],
                  'mime' => $file['mime']
              ]);
            }
        });
    }
}
