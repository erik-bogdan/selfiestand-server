<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Image as ImageModel;
use Validator;
use Illuminate\Http\Request;
use Mail;
use FFMpeg;
use FFMpeg\Format\Video\X264;
use Spatie\Image\Image as ImageSpatie;
use Intervention\Image\Facades\Image;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        if (!$request->hasFile('fileName')) {
            return response()->json(['upload_file_not_found'], 400);
        }

    
        return $this->uploadImages($request->file('fileName'), $request->get('emails'));
    }

    public function uploadImages($images = [], $emails = [])
    {
        $allowedfileExtension=['pdf','jpg','png', 'jpeg', 'JPEG', 'PNG', 'JPG', 'HEIC'];
        $files = $images;
        $errors = [];

        foreach ($files as $file) {
            $extension = $file->getClientOriginalExtension();

            $check = in_array($extension, $allowedfileExtension);

            $uploadedFiles = [];
            if ($check) {
                // var_dump(\Storage::disk('local')->size('public/rendezvenyek/files.txt'));
                //die;
                $folderName = time();
                foreach ($images as $key => $mediaFiles) {
                    if (count($images) > 1) {
                        dump($mediaFiles);
                        if (!\File::isDirectory('rendezvenyek/'.date('Y_m_d'). '/' . $folderName)) {
                            \File::makeDirectory('rendezvenyek/'.date('Y_m_d'). '/' . $folderName, 0777, true, true);
                        }

                        $path = $mediaFiles->storeAs('public/rendezvenyek/'.date('Y_m_d'). '/'.$folderName, $mediaFiles->getClientOriginalName());
                    } else {
                        $path = $mediaFiles->store('public/rendezvenyek/'.date('Y_m_d'));
                        $res = ImageSpatie::load(\Storage::path($path))
                        ->width(1920)
                        ->save();
                    }
                    $name = $mediaFiles->getClientOriginalName();
                    $uploadedFiles[] = [
                        'file_path' => 'rendezvenyek/'.date('Y_m_d'). '/'.$folderName . '/' .$name,
                        'emails' => $emails[$key]
                    ];
                }

                foreach ($uploadedFiles as $uploadedItem) {
                    $newImage = ImageModel::create([
                        'image_path' => $uploadedItem['file_path'],
                        'emails' => $uploadedItem['emails']
                    ]);
                }

                if (count($images) > 1) {
                    //$this->convertToVideo('public/rendezvenyek/'.date('Y_m_d'). '/1654937034/image-%03d.jpg');
                }
            } else {
                return response()->json(['invalid_file_format'], 422);
            }

            return response()->json(['file_uploaded'], 200);
        }
    }

    public function sendMail(Request $request)
    {
        $data["email"] = $request->get('email');
        $data["title"] = "Fényképek";
        $data["body"] = "This is Demo";

        $files = [];

        if ($request->hasFile('fileName')) {
            foreach ($request->file('fileName') as $file) {
                $files[] = [
                    'pathToFile' => $file->getRealPath(),
                    'as' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
              ];
            }
        }


        Mail::send('emails.imageMail', $data, function ($message) use ($data, $files) {
            $message->to($data["email"], $data["email"])
                  ->subject($data["title"]);

            foreach ($files as $file) {
                $message->attach($file['pathToFile'], [
                  'as' => $file['as'],
                  'mime' => $file['mime']
              ]);
            }
        });
    }

    public function convertToVideo($image)
    {
        FFMpeg::open($image)
        ->export()
        ->asTimelapseWithFramerate(1)
        //->addFilter('-r', 30)
        ->inFormat(new X264)
        ->save('public/rendezvenyek/timelapse.mp4');
    }
}
