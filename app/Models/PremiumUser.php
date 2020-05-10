<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PremiumUser extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'ID';
    protected $connection = 'mysql_premium';
    public $timestamps = false;

    public function teamSpeakIdentities()
    {
        return $this->hasMany(TeamSpeakIdentity::class, 'UserId', 'UserId');
    }
}
