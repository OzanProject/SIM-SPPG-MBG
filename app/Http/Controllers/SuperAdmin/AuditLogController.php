<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        // Optional filtering by action
        if ($request->has('action') && $request->action != '') {
            $query->where('action', $request->action);
        }

        // Optional filtering by module (model_type)
        if ($request->has('module') && $request->module != '') {
            $query->where('model_type', 'like', "%{$request->module}%");
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('super-admin.audit-logs.index', compact('logs'));
    }
}
