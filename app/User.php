<?php

namespace App;

use App\System\ApplicationRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

//use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The conversations that belong to the user.
     */
    public function conversations()
    {
        return $this->belongsToMany('App\Conversations')
            ->using('App\UserConversation');
    }

    /**
     * Determines if user is a superadmin (can do everything or not)
     * @return bool
     */
    public function isSuperAdmin(): bool{
        return $this->hasRole(ApplicationRoles::SUPERADMIN);
    }


    // TODO: proxify the permission management methods with isSuperAdmin() checkup

}
