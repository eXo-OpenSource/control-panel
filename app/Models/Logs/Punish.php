<?php

namespace App\Models\Logs;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Punish extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'Punish';
    protected $connection = 'mysql_logs';
    public $timestamps = true;
    public const CREATED_AT = 'Date';
    public const UPDATED_AT = null;

    protected $dates = ['Date'];

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function admin()
    {
        return $this->hasOne(User::class, 'Id', 'AdminId');
    }

    public function log()
    {
        return $this->hasMany(PunishLog::class, 'PunishId', 'Id');
    }

    public function hasFixedEndDate()
    {
        return $this->Type !== 'prison' && $this->Type !== 'unprison';
    }
}
