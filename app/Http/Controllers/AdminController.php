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

class AdminController extends Controller
{
    public function downloadFile(Request $request, $id)
    {
        $file = \App\Models\Image::where('id', $id)->first();
        $myfile = public_path('/storage/' . $file->manipulated_path);
        return response()->download($myfile);
    }

    public function printImage(Request $request, $id)
    {
        $file = \App\Models\Image::where('id', $id)->first();
        $project = $file->project;
        $myfile = public_path('/storage/' . $file->image_path);
        $frame = public_path('/storage/' . $project->frame_image);

        $imgNew = Image::make($myfile)->resize(778, null, function ($constraint) {
            $constraint->aspectRatio();
        })
        ->flip('h')
        ->rotate(-90)
        ;

        $frameNew = Image::make($frame)
        ->insert($imgNew, 'top-left', 22, 20)
        ->save(public_path('storage/test.jpg'))

        ;

        $finalImagePath = public_path('storage/test.jpg');
        $finalImageTransform = Image::make($finalImagePath)
       
        ->save(public_path('storage/test.jpg'));

        return ['data' => asset('storage/test.jpg')];
        return response()->file(public_path('storage/test.jpg'));
    }
}
