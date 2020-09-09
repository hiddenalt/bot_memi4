<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Mimey\MimeTypes;

class StorageController extends Controller {

    // Storage available to be read from web
    private array $publicWebStorage = ["meme_created", "meme_generated", "pic_bank", "public"];
    // Black list of filenames will be aborted as 404 page
    private array $blacklistedFilenames = [".gitignore", ".htaccess"];


    /**
     * Access of storage files via web
     * @param Request $request
     * @param string $storage
     * @param string $filename
     * @return \Illuminate\Http\Response
     * @throws FileNotFoundException
     */
    public function request(Request $request, string $storage, string $filename) {
        // Abort if storage is not public
        if(!in_array($storage, $this->publicWebStorage)) abort(404);

        $disk = Storage::disk($storage);
        if(
            !$disk->exists($filename) ||                        // If not found
            in_array($filename, $this->blacklistedFilenames)    // If blacklisted
        ) abort(404);

        $access = $request->get("access_token");
        switch($storage){

            case "meme_created":
                // TODO: access validation for created memes

                break;

            case "meme_generated":
                // TODO: access validation for generated memes (personal channels)
                break;
        }

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
    }
}
