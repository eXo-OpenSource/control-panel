<?php

namespace App\Models\Logs;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Damage extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'Damage';
    protected $connection = 'mysql_logs';
    public $timestamps = false;

    protected $dates = ['StartTime', 'Date'];

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function target()
    {
        return $this->hasOne(User::class, 'Id', 'TargetId');
    }
}
