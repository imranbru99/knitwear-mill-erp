<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

trait HasFactoryAudit
{
    /** @var array<string, bool> */
    protected static array $branchColumnCache = [];

    public static function bootHasFactoryAudit(): void
    {
        static::creating(function (Model $model): void {
            $userId = auth()->id();
            if ($userId && empty($model->getAttribute('created_by'))) {
                $model->setAttribute('created_by', $userId);
            }
            if ($userId && empty($model->getAttribute('updated_by'))) {
                $model->setAttribute('updated_by', $userId);
            }
            if (empty($model->getAttribute('lock_version'))) {
                $model->setAttribute('lock_version', 1);
            }
            $branchId = auth()->user()?->branch_id;
            if ($branchId && empty($model->getAttribute('branch_id')) && static::tableHasBranch($model)) {
                $model->setAttribute('branch_id', $branchId);
            }
        });

        static::updating(function (Model $model): void {
            $original = (int) $model->getOriginal('lock_version');
            if ($model->isDirty('lock_version')) {
                $incoming = (int) $model->getAttribute('lock_version');
                if ($incoming !== $original && $incoming !== $original + 1) {
                    throw new RuntimeException('This document was updated by someone else. Reload it and try again.');
                }
            }
            $model->setAttribute('lock_version', $original + 1);
            if (auth()->id()) {
                $model->setAttribute('updated_by', auth()->id());
            }
        });

        static::created(function (Model $model): void {
            static::writeAudit($model, 'created');
        });

        static::updated(function (Model $model): void {
            static::writeAudit($model, 'updated');
        });

        static::deleted(function (Model $model): void {
            static::writeAudit($model, 'deleted');
        });

        static::addGlobalScope('branch', function (Builder $query): void {
            $user = auth()->user();
            if (! $user || ! $user->branch_id || $user->sees_all_branches) {
                return;
            }
            $model = $query->getModel();
            if (! static::tableHasBranch($model)) {
                return;
            }
            $query->where($model->getTable().'.branch_id', $user->branch_id);
        });
    }

    protected static function tableHasBranch(Model $model): bool
    {
        $table = $model->getTable();
        if ($table === 'branches') {
            return false;
        }

        return static::$branchColumnCache[$table] ??= Schema::hasColumn($table, 'branch_id');
    }

    protected static function writeAudit(Model $model, string $event): void
    {
        if ($model instanceof AuditLog || ! ($model->auditsChanges ?? true)) {
            return;
        }
        if (! Schema::hasTable('audit_logs')) {
            return;
        }

        AuditLog::withoutEvents(function () use ($model, $event): void {
            AuditLog::query()->create([
                'user_id' => auth()->id(),
                'auditable_type' => $model::class,
                'auditable_id' => $model->getKey(),
                'event' => $event,
                'before' => $event === 'created' ? null : $model->getOriginal(),
                'after' => $event === 'deleted' ? null : $model->getAttributes(),
                'branch_id' => $model->getAttribute('branch_id'),
            ]);
        });
    }
}
