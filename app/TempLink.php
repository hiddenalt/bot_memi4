<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string path
 * @property string access_token
 */
class TempLink extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'temp_links';
}
