<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Mimey\MimeTypes;

class Picture extends Model
{
    const STATUS_PENDING = "pending";
    const STATUS_DENIED = "denied";
    const STATUS_ACCEPTED = "accepted";

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'picbank';

    protected string $associatedStorage = 'pic_bank';

    /**
     * Copies and adds new picture to database
     * @param string $url
     * @param int $bankId
     * @param string $status
     */
    public function copyAndCreateNew(string $url, int $bankId, string $status = self::STATUS_ACCEPTED){
        // Copy to tempdir with unique name
        $uid = md5(uniqid(rand() . date("Y-m-d H:i:s"),true));
        $tempImage = tempnam(sys_get_temp_dir(), $uid);
        copy($url, $tempImage);

        // Get suitable extension
        $mimes = new MimeTypes();
        $ext = $mimes->getExtension(mime_content_type($tempImage));
        $filename = $uid . "." . $ext;

        // Put into the storage
        $disk = Storage::disk($this->associatedStorage);
        $disk->put($filename, file_get_contents($tempImage));

        // Delete temp image
        unlink($tempImage);

        // Save to database
        $this->bank_id = $bankId;
        $this->path = $filename;
        $this->status = $status;
        $this->save();
    }

    /**
     * Deletes the picture both from storage and database
     * @throws \Exception
     */
    public function forget(){
        $filename = $this->path;

        $disk = Storage::disk($this->associatedStorage);
        if(!$disk->exists($filename))
            return;

        $disk->delete($filename);
        $this->delete();
    }

    public function getFullStoragePath(){
        return storage_path($this->associatedStorage . DIRECTORY_SEPARATOR . $this->path);
    }

    public function getPublicURL(){
        return env("APP_URL") . '/storage/' . $this->associatedStorage . "/" . $this->path;
    }

}
