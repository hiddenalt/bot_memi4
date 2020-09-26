<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Bank extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'banks_list';

    /**
     * Get the words of the current bank.
     */
    public function words(){
        return $this->hasMany('App\Word', 'bank_id');
    }

    /**
     * Get the pictures of the current bank.
     */
    public function pictures(){
        return $this->hasMany('App\Picture', 'bank_id');
    }


    /**
     * Get the conversation (creator) of the current bank
     * @return HasOne
     */
    public function conversation(){
        return $this->hasOne('App\Conversation', "id", "conversation_id");
    }
}
