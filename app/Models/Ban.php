<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'bans';

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'player_id');
    }

    public function admin()
    {
        return $this->hasOne(User::class, 'Id', 'author');
    }
}
