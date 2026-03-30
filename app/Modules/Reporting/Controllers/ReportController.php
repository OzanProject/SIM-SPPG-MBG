<?php

namespace App\Modules\Reporting\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Reporting\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('Reporting::reports.index');
    }

    public function generate(Request $request)
    {
        $type = $request->input('type');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if ($type == 'neraca') {
            $data = $this->reportService->getNeracaToko($start_date, $end_date);
        } else {
            $data = $this->reportService->getLabaRugi($start_date, $end_date);
        }

        return view('Reporting::reports.result', compact('data', 'type', 'start_date', 'end_date'));
    }
}
