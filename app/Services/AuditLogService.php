<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditLogService
{
    /**
     * Log action.
     */
    public static function log($userId, $companyId, $action, $modelType = null, $modelId = null, $oldValues = [], $newValues = [], $ipAddress = null, $userAgent = null)
    {
        return AuditLog::create([
            'user_id' => $userId,
            'company_id' => $companyId,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }
}
