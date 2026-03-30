<?php

namespace App\Modules\AuditTrail\Services;

use App\Modules\AuditTrail\Models\AuditLog;

class AuditTrailService
{
    public function getAllLogs()
    {
        return AuditLog::with('user')->orderBy('created_at', 'desc')->paginate(50);
    }

    public function logAction(string $action, string $modelType, int $modelId, array $oldValues = null, array $newValues = null)
    {
        return AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
