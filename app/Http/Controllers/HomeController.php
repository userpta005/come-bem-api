<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:panel_view', ['only' => ['show', 'index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $status = OrderStatus::all();

        $filterStatus =  $request->status ?? $status->keys()->all();

        $data = Order::select(
            'orders.*',
            'people.name as dependent',
            'accounts.class',
        )
            ->join('accounts', 'accounts.id', 'orders.account_id')
            ->join('dependents', 'dependents.id', 'accounts.dependent_id')
            ->join('people', 'people.id',  'dependents.person_id')
            ->whereIn('orders.status', $filterStatus)
            ->when(session()->has('store'), function ($query) {
                $query->where('accounts.store_id', session('store')['id']);
            })
            ->with('orderItems.product')
            ->when(!empty($request->date), function ($query) use ($request) {
                $query->whereDate('orders.date', $request->date);
            })
            ->when(!empty($request->turn), function ($query) use ($request) {
                $query->where('orders.turn', $request->turn);
            })
            ->orderBy('orders.id', 'desc')
            ->paginate(25);

        // dd($data->first()->turn->name());

        return view('dashboard', compact(
            'data',
            'status',
            'filterStatus'
        ));
    }
}
