<?php

namespace App;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Conversation
 * @package App
 * @mixin Eloquent
 */
class Conversation extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'conversations';

}
