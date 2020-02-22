<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketAnswer extends Model
{
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = null;
    protected $primaryKey = 'Id';

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class, 'Id', 'TicketId');
    }
}
