<?php

namespace App;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
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


    /**
     * Scope a query to find a conversation by its ID
     *
     * @param Builder $query
     * @param $conversationID
     * @return Builder
     */
    public function scopeOfID($query, $conversationID){
        return $query->where("conversation_id", $conversationID);
    }

    // TODO: get native conversation name

}
