<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            self::logAction('created', $model);
        });

        static::updated(function ($model) {
            self::logAction('updated', $model);
        });

        static::deleted(function ($model) {
            self::logAction('deleted', $model);
        });
    }

    protected static function logAction($action, $model)
    {
        try {
            $user = auth()->user();
            
            $changes = [];
            if ($action === 'created') {
                $changes = ['new' => $model->getAttributes()];
            } elseif ($action === 'updated') {
                $changes = [
                    'old' => array_intersect_key($model->getOriginal(), $model->getDirty()),
                    'new' => $model->getDirty(),
                ];
            } elseif ($action === 'deleted') {
                $changes = ['old' => $model->getAttributes()];
            }

            AuditLog::create([
                'user_id' => $user ? $user->id : null,
                'action' => $action,
                'model_type' => get_class($model),
                'model_id' => $model->getKey() ? (string)$model->getKey() : null,
                'changes' => $changes,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to create audit log: " . $e->getMessage());
        }
    }
}
