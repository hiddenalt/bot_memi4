<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chain extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'markov_chain';

    protected $fillable = ['bank_id', 'target', 'next'];

    /**
     * Get the target word record associated with the user.
     */
    public function target()
    {
        return $this->hasOne('App\Word', 'id', 'target');
    }

    /**
     * Get the next word record associated with the user.
     */
    public function next()
    {
        return $this->hasOne('App\Word', 'id', 'next');
    }
}
