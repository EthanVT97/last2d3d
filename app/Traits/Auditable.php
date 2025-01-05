<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            static::logChanges('created', $model);
        });

        static::updated(function ($model) {
            static::logChanges('updated', $model);
        });

        static::deleted(function ($model) {
            static::logChanges('deleted', $model);
        });
    }

    protected static function logChanges($action, $model)
    {
        if (!auth()->check()) {
            return;
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'old_values' => $action === 'updated' ? $model->getOriginal() : [],
            'new_values' => $model->getAttributes(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
