<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Common\Status;
use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:client-dependents-report_view', ['only' => ['clientDependents']]);
        $this->middleware('permission:stock-report_view', ['only' => ['stocks']]);
    }

    public function clientDependents(Request $request)
    {
        $stores = Store::query()
        ->person()
        ->where('stores.status', Status::ACTIVE)
        ->get();

        return view('reports.client-dependents.index', compact('stores'));
    }

    public function stocks(Request $request)
    {
        $stores = Store::query()
        ->person()
        ->where('stores.status', Status::ACTIVE)
        ->get();

        return view('reports.stocks.index', compact('stores'));
    }
}
