<?php

namespace App\Models\Training;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class Training extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    const TRAINING_STATE_IN_PROGRESS = 0;
    const TRAINING_STATE_FINISHED = 1;

    protected $table = 'trainings';
    protected $primaryKey = 'Id';
    public const CREATED_AT = 'CreatedAt';
    public const UPDATED_AT = 'UpdatedAt';

    protected $dates = [
        'CreatedAt',
        'UpdatedAt',
        'CreatedAt',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function template()
    {
        return $this->hasOne(Template::class, 'Id', 'TemplateId');
    }

    public function contents()
    {
        return $this->hasMany(TrainingContent::class, 'TrainingId', 'Id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'training_users', 'TrainingId', 'UserId')->withPivot('Role');
    }
    /*
    public function users()
    {
        return $this->hasMany(TrainingUser::class, 'TrainingId', 'Id');
    }
    */
}

