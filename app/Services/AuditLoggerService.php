<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLoggerService
{
    public function log(string $module, string $action, string $description, array $oldValues = null, array $newValues = null)
    {
        try {
            $user = Auth::user();

            AuditLog::create([
                'user_id' => $user?->id,
                'role' => $user?->role ?? 'system',
                'module' => $module,
                'action' => $action,
                'description' => $description,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error('Audit Logging failed: ' . $th->getMessage());
        }
    }
}
