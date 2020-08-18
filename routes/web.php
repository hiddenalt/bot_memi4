<?php

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

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mimey\MimeTypes;

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/api', 'BotManController@handle');
//Route::post('/api', 'BotManController@handle');
//Route::get('/chat', 'BotManController@tinker'); // Temporarily disabled

// For admin application
Route::get('/admin{any}', 'FrontendController@admin')->where('any', '.*');
// For public application
Route::any('/menu{any}', 'FrontendController@app')->where('any', '.*'); //^(?!api).*$


// Storage access
Route::get('/storage/{storage}/{filename}', function (Request $request, string $storage, string $filename) {
    if(!in_array($storage, ["meme_created", "meme_generated", "pic_bank", "public"])) abort(404);

    $disk = Storage::disk($storage);
    if(!$disk->exists($filename)) abort(404);


    $access = $request->get("access_token");
    switch($storage){

        case "meme_created":
            // TODO: access validation for created memes

            break;

        case "meme_generated":
            // TODO: access validation for generated memes (personal channels)
            break;
    }

    if(in_array($filename, [".gitignore", ".htaccess"])) abort(404);


//    $path = storage_path('app/' . $storage . "/" . $filename);
//    $file = File::get($path);
//    $type = File::mimeType($path);

    // File response
    $response = Response::make($disk->get($filename), 200);
    $response->header("Content-Type", (new MimeTypes())->getMimeType(pathinfo($filename, PATHINFO_EXTENSION)));


    // Self-destruction for specific categories
    switch($storage){
        case "meme_created":
            $disk->delete($filename);
            break;
    }


    return $response;
})->name('meme_created');