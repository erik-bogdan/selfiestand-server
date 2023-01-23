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
        $myfile = public_path('/storage/' . $file->image_path);
        return response()->download($myfile);
    }

    public function printImage(Request $request, $id)
    {
        $file = \App\Models\Image::where('id', $id)->first();
        $myfile = public_path('/storage/' . $file->image_path);
        return ['data' => asset('/storage/' . $file->image_path)];
        return response()->file(public_path('storage/test.jpg'));
    }
}
