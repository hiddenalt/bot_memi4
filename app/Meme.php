<?php

namespace App;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Model;

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
}
