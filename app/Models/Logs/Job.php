<?php

namespace App\Models\Logs;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $primaryKey = 'ID';
    protected $table = 'Job';
    protected $connection = 'mysql_logs';
    public $timestamps = false;

    protected $dates = ['Date'];

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserID');
    }
}
