<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamspeakIdentity extends Model
{
    protected $table = 'teamspeak_identity';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function getTypeName()
    {
        if ($this->Type === 1) {
            return 'Benutzer';
        }
        return 'Musikbot';
    }
}
