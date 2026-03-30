<?php

namespace App\Modules\Procurement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Procurement\Services\ProcurementService;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    protected $procurementService;

    public function __construct(ProcurementService $procurementService)
    {
        $this->procurementService = $procurementService;
    }

    public function index()
    {
        $purchase_orders = $this->procurementService->getAllPurchaseOrders();
        return view('Procurement::purchase_orders.index', compact('purchase_orders'));
    }
}
