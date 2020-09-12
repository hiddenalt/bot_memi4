<?php

namespace App;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class Meme
 * @property string category
 * @property string type
 * @property bool is_public
 * @property string filename
 * @property string owner_id
 * @package App
 * @mixin Eloquent
 */
class Meme extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'memes';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
//    public $incrementing = true;

    /**
     * Deletes meme both from directory and database
     * @throws \Exception
     */
    public function forget(){
        $filename = $this->filename;
        $category = $this->category;

        $disk = Storage::disk($category);
        if(!$disk->exists($filename))
            return;

        $disk->delete($filename);
        $this->delete();
    }
}
