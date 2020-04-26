<?php

namespace App\Models\Training;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class TemplateContent extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $table = 'training_template_contents';
    protected $primaryKey = 'Id';
    public const CREATED_AT = 'CreatedAt';
    public const UPDATED_AT = 'UpdatedAt';
    public const DELETED_AT = 'DeletedAt';

    protected $dates = [
        'CreatedAt',
        'UpdatedAt',
        'CreatedAt',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'Id');
    }

    public function template()
    {
        return $this->hasOne(Template::class, 'Id', 'TrainingTemplateId');
    }
}

