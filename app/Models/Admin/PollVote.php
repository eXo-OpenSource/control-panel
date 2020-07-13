<?php


namespace App\Models\Admin;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    protected $table = 'admin_poll_vote';
    public const CREATED_AT = 'CreatedAt';
    public const UPDATED_AT = null;

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'AdminId');
    }
}
