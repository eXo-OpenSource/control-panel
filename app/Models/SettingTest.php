<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingTest extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'settings';
    public $timestamps = false;
    protected $connection = 'mysql_test';
}
