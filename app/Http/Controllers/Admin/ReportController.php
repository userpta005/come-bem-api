<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Store;
use App\Models\Cashier;
use App\Enums\Common\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:client-dependents-report_view', ['only' => ['clientDependents']]);
        $this->middleware('permission:stocks-report_view', ['only' => ['stocks']]);
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
