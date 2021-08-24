<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Logs\Login;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IpHub extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'iphub';
    protected $connection = 'mysql';

    public const CREATED_AT = 'CreatedAt';
    public const UPDATED_AT = 'UpdatedAt';

    public function logins()
    {
        return $this->hasMany(Login::class, 'Ip', 'Ip');
    }
}
