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

use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', function () {
    return view('welcome');
});

//Route::get("/assets{any}", function($pathToFile){
//    return response()
//        ->header('Content-Type', 'text/plain')
//        ->download(asset($pathToFile, false));
//    return response("sad");
//})
//    ->where('any', '.*')
//    ->name("public.assets");

//Route::match(['get', 'post'], '/assets/{filename}', function ($filename){
//    $path = asset($filename);
//
//    try{
//        $file = File::get($path);
//        $type = File::mimeType($path);
//
//        $response = Response::make($file, 200);
//        $response->header("Content-Type", $type);
//    } catch (Exception $exception){
//        abort(404);
//    }
//
//
//    return \response("asd");
//
//    return $response;
//})->where('filename', '.*');

// Bot API
Route::match(['get', 'post'], '/api', 'BotManController@handle');
//Route::post('/api', 'BotManController@handle');
//Route::get('/chat', 'BotManController@tinker'); // Temporarily disabled

// Admin application
Route::get('/admin{any}', 'FrontendController@admin')
    ->where('any', '.*')
    ->name("frontend.app.admin");

// Public application
Route::any('/menu{any}', 'FrontendController@app')
    ->where('any', '.*') //^(?!api).*$
    ->name("frontend.app");

// Storage access
Route::get('/storage/{storage}/{filename}', "StorageController@request")
    ->name('storage');