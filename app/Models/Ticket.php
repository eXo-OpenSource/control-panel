<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;
    const DELETED_AT = 'DeletedAt';
    const UPDATED_AT = 'LastResponseAt';
    const CREATED_AT = 'CreatedAt';
    protected $primaryKey = 'Id';


    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function assignee()
    {
        return $this->hasOne(User::class, 'Id', 'AssigneeId');
    }

    public function resolver()
    {
        return $this->hasOne(User::class, 'Id', 'ResolvedBy');
    }

    public function category()
    {
        return $this->hasOne(TicketCategory::class, 'Id', 'CategoryId');
    }

    public function answers()
    {
        return $this->hasMany(TicketAnswer::class, 'TicketId', 'Id');
    }
}
