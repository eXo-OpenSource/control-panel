<?php

namespace App\Models\Training;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class Template extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $table = 'training_templates';
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
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function contents()
    {
        return $this->hasMany(TemplateContent::class, 'TrainingTemplateId', 'Id');
    }

    public function element()
    {
        return $this->morphTo('template', 'ElementType', 'ElementId', 'Id');
    }

    public function getTarget()
    {
        if($this->ElementType === 2) {
            return __('Fraktion');
        } elseif($this->ElementType === 3) {
            return __('Unternehmen');
        }
    }
}

