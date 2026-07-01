<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public static function log(
        string $action,
        string $description = '',
        string $model = null,
        int $modelId = null
    ): void {
        DB::table('audit_logs')->insert([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'model'       => $model,
            'model_id'    => $modelId,
            'description' => $description,
            'ip_address'  => Request::ip(),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}