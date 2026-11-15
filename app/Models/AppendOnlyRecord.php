<?php

namespace App\Models;

use App\Models\Concerns\HasFactoryAudit;
use Illuminate\Database\Eloquent\Model;

class AppendOnlyRecord extends Model
{
    use HasFactoryAudit;

    public bool $auditsChanges = false;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'lock_version' => 'integer',
        ];
    }
}
