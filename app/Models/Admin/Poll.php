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
    protected $dates = ['CreatedAt', 'UpdatedAt', 'FinishedAt'];

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
        $data['CreatedAt'] = $data['CreatedAt'] ? (new \Carbon\Carbon($data['CreatedAt']))->format('d.m.Y H:i:s') : null;
        $data['UpdatedAt'] = $data['UpdatedAt'] ? (new \Carbon\Carbon($data['UpdatedAt']))->format('d.m.Y H:i:s') : null;
        $data['FinishedAt'] = $data['FinishedAt'] ? (new \Carbon\Carbon($data['FinishedAt']))->format('d.m.Y H:i:s') : null;

        $data['votes'] = [];

        foreach ($this->votes()->with('user')->get() as $vote)
        {
            array_push($data['votes'], [
                'AdminId' => $vote->AdminId,
                'Admin' => $vote->user ? $vote->user->Name : 'Unbekannt',
                'Vote' => $vote->Vote,
                'CreatedAt' => (new \Carbon\Carbon($vote->CreatedAt))->format('d.m.Y H:i:s'),
            ]);
        }

        return $data;
    }
}
