<?php

namespace App\Modules\AuditTrail\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AuditTrail\Services\AuditTrailService;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    protected $auditTrailService;

    public function __construct(AuditTrailService $auditTrailService)
    {
        $this->auditTrailService = $auditTrailService;
    }

    public function index()
    {
        $logs = $this->auditTrailService->getAllLogs();
        return view('AuditTrail::audit_logs.index', compact('logs'));
    }
}
