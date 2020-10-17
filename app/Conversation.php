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

    /**
     * Get the users that owns the conversation.
     * Note: 1 user = 1 conversation!
     * @return User|null
     */
    public function user()
    {
        return $this->belongsToMany('App\User', 'user_has_conversation')->first();
    }


    public function setDefaultLocale(string $langCode){
        $this->language = $langCode;
        $this->save();
    }

    // TODO: get native conversation name

}
