<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wordbank';

    protected $fillable = ["bank_id", "text", "type", "status"];
}
