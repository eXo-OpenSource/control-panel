<?php


namespace App\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FcmNotification extends Model
{
    protected $primaryKey = 'UserId';

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }
}
