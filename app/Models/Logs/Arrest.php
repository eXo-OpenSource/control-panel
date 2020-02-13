<?php

namespace App\Models\Logs;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Arrest extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'Arrest';
    protected $connection = 'mysql_logs';
    public $timestamps = false;

    protected $dates = ['Date'];

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function police()
    {
        return $this->hasOne(User::class, 'Id', 'PoliceId');
    }
}
