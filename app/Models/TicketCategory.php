<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    protected $primaryKey = 'Id';

    public function fields()
    {
        return $this->hasMany(TicketCategoryField::class, 'CategoryId', 'Id');
    }
}
