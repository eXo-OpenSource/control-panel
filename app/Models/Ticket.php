<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;
    const DELETED_AT = 'DeletedAt';
    const UPDATED_AT = 'UpdatedAt';
    protected $primaryKey = 'Id';
}
