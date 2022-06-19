<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Image as ImageModel;
use Mail;

class SendImageEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'selfiestand:send-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Feldolgozott fényképek elküldése';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data["title"] = "SelfieStand fényképed";
        $data["body"] = "";

      
        $images = ImageModel::where([
            ['manipulated_path', '!=', null],
            ['emails', '!=', null],
            ['emails', '!=', '[]'],
            ['email_sent', '=', 0]
        ])->get();
        $counter = 1;
        $all = count($images);
        foreach ($images as $image) {
            $decodedMails = json_decode($image->emails);
            if (count($decodedMails) > 0) {
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
                $this->info('Sikeres email kiküldés: ' . $counter . '/' . $all);
                $counter ++;

                $image->email_sent = 1;
                $image->save();
            }
        }

        $this->info('Sikeres volt az emailek kiküldése!');
    }
}
