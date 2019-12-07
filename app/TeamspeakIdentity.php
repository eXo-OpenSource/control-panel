<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
