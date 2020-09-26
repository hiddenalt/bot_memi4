<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserConversation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_has_conversation';

    /**
     * Get the user record associated with the mapping.
     */
    public function user()
    {
        return $this->hasOne('App\User');
    }

    /**
     * Get the conversation record associated with the mapping.
     */
    public function conversation()
    {
        return $this->hasOne('App\Conversation');
    }

}
