<?php

namespace App\Models\Logs;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class GangwarStatistic extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'GangwarStatistics';
    protected $connection = 'mysql_logs';
    public $timestamps = true;
    public const CREATED_AT = 'Date';
    public const UPDATED_AT = null;

    protected $dates = ['Date'];

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }
}
