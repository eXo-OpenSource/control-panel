<?php

namespace App\Models\Training;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class TrainingContent extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'training_contents';
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

    public function training()
    {
        return $this->hasOne(Training::class, 'Id', 'TemplateId');
    }

    public function templateContent()
    {
        return $this->hasOne(TemplateContent::class, 'Id', 'TrainingContentId');
    }
}

