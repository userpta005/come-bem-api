<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Common\Status;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Stock;
use App\Models\Store;
use Illuminate\Http\Request;

class StockReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:stocks-report_view');
    }

    public function __invoke(Request $request)
    {
        $store = Store::query()->person()->findOrFail($request->store_id);

        $stocks = Stock::query()
            ->where('store_id', $request->store_id)
            ->get();

        return view('reports.stocks.report', compact('store', 'stocks'));
    }
}
