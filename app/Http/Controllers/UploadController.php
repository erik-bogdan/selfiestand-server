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
use App\Models\Project;
use App\Models\FailedEmail;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $projectUuid = $request->get('project_uuid');
        $fileUuid = $request->get('file_uuid');
        $image = $request->file('file');

        if (!$request->hasFile('file')) {
            return response()->json(['upload_file_not_found'], 400);
        }
        
        if (!$projectUuid) {
            return response()->json(['uuid_not_found'], 400);
        }
       
        if (!$fileUuid) {
            return response()->json(['file_uuid_not_found'], 400);
        }

        $project = Project::where('uuid', $projectUuid)->first();
        if (!$project) {
            return response()->json(['project_not_found'], 403);
        }

       $fileIsExists = ImageModel::where([
        ['project_id', $project->id],
        ['uuid', $fileUuid]
       ])->first();

       if ($fileIsExists) {
        return response()->json(['file_already_exists'], 403);
       }


        if (!\File::isDirectory('rendezvenyek/'. $projectUuid)) {
            \File::makeDirectory('rendezvenyek/'. $projectUuid, 0777, true, true);
        }

        if (!\File::isDirectory(public_path('storage/rendezvenyek/' . $projectUuid . '/thumbnail'))) {
            \File::makeDirectory(public_path('storage/rendezvenyek/' . $projectUuid . '/thumbnail'), 0777, true, true);
        }

        $path = $image->storeAs('public/rendezvenyek/' . $projectUuid, $image->getClientOriginalName());

        $newImage = ImageModel::create([
            'image_path' => str_replace("public/", "", $path),
            'emails' => [],
            'project_id' => $project->id,
            'uuid' => $fileUuid
        ]);

        $img = \Storage::disk('public')->get($newImage->image_path);

        $manipulatedPublicPathThumbnail = public_path('storage/rendezvenyek/' . $projectUuid . '/thumbnail/' . $image->getClientOriginalName());

        $imgNew = Image::make($img)->resize(384, null, function ($constraint) {
            $constraint->aspectRatio();
        })
        ->save($manipulatedPublicPathThumbnail)
        ;
        $newImage->thumbnail_path = 'rendezvenyek/' . $projectUuid . '/thumbnail/' . $imgNew->basename;
        $newImage->save();
  
        //return $this->uploadImages($request->file('fileName'), $request->get('emails'), $request->get('event_id'));
    }

    public function uploadImages($images = [], $emails = [], $eventId = null)
    {
        $allowedfileExtension=['pdf','jpg','png', 'jpeg', 'JPEG', 'PNG', 'JPG', 'HEIC'];
        $files = $images;
        $errors = [];
        dump($files);

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
                        'emails' => $emails[$key],
                        'event_id' => $eventId
                    ];
                }

                foreach ($uploadedFiles as $uploadedItem) {
                    $newImage = ImageModel::create([
                        'image_path' => $uploadedItem['file_path'],
                        'emails' => $uploadedItem['emails'],
                        'event_id' => $uploadedItem['event_id'],
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
        $photopUuid = $request->get('uuid');

        if (!$photopUuid) {
            return response()->json(['photo_uuid_not_found'], 403);
        }
       
        $imageRecord = ImageModel::where('uuid', $photopUuid)->first();


        if (!$imageRecord) {
            $newFailedEmail = FailedEmail::create([
                'uuid' => $photopUuid,
                'email' => $request->get('email'),
                'is_success' => false,
                'attempts' => 1,
                'first_attempt' => new \DateTime(),
                'last_attempt' => new \DateTime(),
            ]);

            return response()->json(['image_record_not_found'], 404);
        }

        $imageUrl = \Storage::disk('public')->get($imageRecord->image_path);
        $imagePath = \Storage::disk('public')->path($imageRecord->image_path);

        $image = Image::make($imagePath);

        $data["email"] = $request->get('email');
        $data["title"] = "SelfieStand fényképed";

        $files = [];


      /*  Mail::send('emails.imageMail', $data, function ($message) use ($data, $image, $imageUrl) {
            $message->to($data["email"], $data["email"])
                  ->subject($data["title"])
                  ->with([
                    'imageUrl' => $imageUrl
                  ]);

                  
        });*/
        $emails = $imageRecord->emails;
        //var_dump(array_push($emails, $request->get('email'));die;
        $imageRecord->emails = array_push($emails, $request->get('email')); //TODO: Valamiért 1et rak be ide    
        $imageRecord->email_sent = 1;
        $imageRecord->save();
        Mail::to($data["email"])
        ->send(new \App\Mail\ImageMail($imageUrl, $image->basename));

    }

    public function convertToVideo($image)
    {
        FFMpeg::open($image)
        ->export()
        ->asTimelapseWithFramerate(1)
        //->addFilter('-r', 30)
        ->inFormat(new X264())
        ->save('public/rendezvenyek/timelapse.mp4');
    }
}
