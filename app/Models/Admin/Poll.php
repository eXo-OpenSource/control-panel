<?php


namespace App\Models\Admin;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'admin_poll';
    public const CREATED_AT = 'CreatedAt';
    public const UPDATED_AT = 'UpdatedAt';

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'AdminId');
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class, 'PollId', 'Id');
    }

    public function toArray()
    {
        $data = parent::toArray();
        $data['Admin'] = $this->user ? $this->user->Name : 'Unbekannt';

        $data['votes'] = [];

        foreach ($this->votes()->with('user')->get() as $vote)
        {
            array_push($data['votes'], [
                'AdminId' => $vote->AdminId,
                'Admin' => $vote->user ? $vote->user->Name : 'Unbekannt',
                'Vote' => $vote->Vote,
                'CreatedAt' => $vote->CreatedAt,
            ]);
        }

        return $data;
    }
}
