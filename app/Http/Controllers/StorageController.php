<?php

namespace App\Http\Controllers;

use App\Meme;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Mimey\MimeTypes;

class StorageController extends Controller {

    // Storage available to be read from web
    private array $publicWebStorage = ["meme_created", "meme_generated", "pic_bank", "public"];
    // Meme storage list
    private array $memeWebStorage = ["meme_created", "meme_generated"];
    // Black list of filenames will be aborted as 404 page
    private array $blacklistedFilenames = [".gitignore", ".htaccess"];

    /** Current request info */
    private string $filename = "";
    private Filesystem $disk;
    private Request $request;
    private string $storage = "";

    /**
     * Access storage files via web
     * @param Request $request
     * @param string $storage
     * @param string $filename
     * @return \Illuminate\Http\Response
     * @throws FileNotFoundException
     */
    public function request(Request $request, string $storage, string $filename) {

        $this->request = $request;
        $this->filename = $filename;
        $this->storage = $storage;

        // Abort if storage is not public
        if(!in_array($storage, $this->publicWebStorage)) abort(404);

        $this->disk = Storage::disk($storage);
        if(
            !$this->disk->exists($filename) ||                  // If not found
            in_array($filename, $this->blacklistedFilenames)    // If blacklisted
        ) abort(404);


        // Meme response
        if(in_array($storage, $this->memeWebStorage))
            return $this->memeResponse();

        // Default storage response
        return $this->serializeResponse();
    }

    /**
     * Sends meme file
     * @return \Illuminate\Http\Response
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function memeResponse(){
//        $access = $this->request->get("access_token");
        switch($this->storage){
            case "meme_created":
                // TODO: access validation for created memes

                break;

            case "meme_generated":
                // TODO: access validation for generated memes (personal channels)
                break;
        }

        /** @var Meme $meme */
        $meme = Meme::query()->where("filename", $this->filename)->first();
        if($meme == null) abort(404);

        $response = $this->serializeResponse();

        // Self-destroy for meme_created
        switch($this->storage){
            case "meme_created":
                $meme->forget();
                break;
        }

        return $response;
    }

    /**
     * Creates file response
     * @return \Illuminate\Http\Response
     * @throws FileNotFoundException
     */
    public function serializeResponse(){
        $response = Response::make($this->disk->get($this->filename), 200);
        $response->header("Content-Type", (new MimeTypes())->getMimeType(pathinfo($this->filename, PATHINFO_EXTENSION)));

        return $response;
    }

}
