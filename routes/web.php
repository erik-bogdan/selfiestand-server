<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('api/image-upload', [App\Http\Controllers\UploadController::class, 'store']);
Route::post('api/send-mail', [App\Http\Controllers\UploadController::class, 'sendMail']);
Route::get('api/manipulate', [App\Http\Controllers\UploadController::class, 'manipulateImages']);