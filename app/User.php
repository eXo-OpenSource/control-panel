<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

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

    public function warns()
    {
        return $this->hasMany(Warn::class, 'userId', 'Id');
    }

    public function isBanned()
    {
        $time = (new \DateTime())->getTimestamp();
        $bans = DB::table('bans')
            ->where('player_id', $this->Id)
            ->where(function($query) use ($time) {
                $query->where('expires', '>=', $time)
                    ->orWhere('expires', 0);
            })
            ->orderBy('expires', 'DESC')
            ->get();

        if (sizeof($bans) == 0) {
            $warns = DB::table('warns')->where('userId', $this->Id)->where('expires', '>=', $time)->orderBy('expires', 'DESC')->limit(3)->get();

            if (sizeof($warns) == 3) {
                return $warns[2]->expires;
            }
            return false;
        }

        return $bans[0]->expires;
    }
}
