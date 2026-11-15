<?php

namespace App\Models;

use App\Models\Concerns\HasFactoryAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FactoryRecord extends Model
{
    use HasFactoryAudit;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'lock_version' => 'integer',
        ];
    }
}
