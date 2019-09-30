<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'account';
    protected $primaryKey = 'Id';
    public $remember_token = false;
    protected $connection = 'mysql';
    protected $dates = ['LastLogin', 'RegisterDate'];
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function character()
    {
        return $this->hasOne(Character::class, 'Id', 'Id');
    }

    public function textures()
    {
        return $this->hasMany(Texture::class, 'UserId', 'Id');
    }
}
