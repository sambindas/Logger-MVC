<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'user_name', 'email', 'password', 'user_role', 'phone', 'date_added', 'status', 'user_type', 'state_id'
    ];

    protected $hidden = [
        'password', 'password_token',
    ];

    protected $table = 'user';
    public $timestamps = false;
}
