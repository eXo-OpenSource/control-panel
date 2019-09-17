<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountActivity extends Model
{
    protected $table = "accountActivity";

    protected $dates = [
        'Date'
    ];
}

