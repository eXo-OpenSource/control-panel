<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccountMod extends Model
{
    protected $table = "account_mods";
    protected $dates = ['CreatedAt', 'LastSeenAt'];
}

