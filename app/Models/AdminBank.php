<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AdminBank extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'server_bank_accounts';

    public function getName()
    {
        return 'Admin Kasse';
    }

    public function getURL()
    {
        return null;
    }
}
