<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:order-summary-report_view', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $data = null;

        if ($request->filled(['date_start', 'date_end'])) {
            $data = Order::select(
                'orders.*',
                'people.name as dependent',
                'accounts.class',
                \DB::raw('(select sum(order_items.quantity) from order_items where order_items.order_id = orders.id) as total_quantity'),
            )
                ->join('accounts', 'accounts.id', 'orders.account_id')
                ->join('dependents', 'dependents.id', 'accounts.dependent_id')
                ->join('people', 'people.id', 'dependents.person_id')
                ->where('orders.status', 2)
                ->when(session()->has('store'), function ($query) {
                    $query->where('accounts.store_id', session('store')['id']);
                })
                ->when(!empty($request->search), function ($query) use ($request) {
                    $query->whereHas('account', function ($que) use ($request) {
                        $que->where('accounts.dependent_id', $request->search);
                    });
                })
                ->when(!empty($request->date_start), function ($query) use ($request) {
                    $query->whereDate('orders.date', '>=', $request->date_start);
                })
                ->when(!empty($request->date_end), function ($query) use ($request) {
                    $query->whereDate('orders.date', '<=', $request->date_end);
                })
                ->orderBy('date', 'asc')
                ->get();

        }

        return view('reports.order-summary.index', compact('data'));
    }

}
