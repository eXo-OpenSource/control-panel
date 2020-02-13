<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    protected $primaryKey = 'Id';

    public function getName()
    {
        return 'Haus' + $this->Id;
    }

    public function getURL()
    {
        return null;
    }
}
