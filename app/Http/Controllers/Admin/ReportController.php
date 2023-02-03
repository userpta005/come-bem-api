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
        $this->middleware('permission:cash-summary-report_view', ['only' => ['cashSummary']]);
        $this->middleware('permission:order-summary-report_view', ['only' => ['orderSummary']]);
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

    public function cashSummary(Request $request)
    {
        $cashiers = Cashier::query()
            ->where('store_id', session('store')['id'])
            ->get();

        $users = User::query()
            ->get();

        return view('reports.cash-summary.index', compact('cashiers', 'users'));
    }

    public function orderSummary(Request $request)
    {
        return view('reports.order-summary.index');
    }
}
