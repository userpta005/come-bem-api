<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Common\Status;
use App\Models\{
    Stock,
    Product,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:stocks_view', ['only' => ['show', 'index']]);
        $this->middleware('store');
    }

    public function index(Request $request)
    {
        $products = Product::query()
            ->where('status', Status::ACTIVE)
            ->where('store_id', session('store')['id'])
            ->orderBy('name')
            ->get();

        $data = Stock::query()
            ->when(!empty($request->product_id), function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })
            ->where('store_id', session('store')['id'])
            ->where('quantity', '>=', '0')
            ->select(
                'product_id',
                'store_id',
                DB::raw('sum(quantity) as quantity')
            )
            ->groupBy('product_id', 'store_id')
            ->orderBy(Product::select('name')->whereColumn('stocks.product_id', 'products.id'))
            ->paginate(10);

        return view('stocks.index', compact(
            'data',
            'products'
        ));
    }

    public function show(Request $request)
    {
        $item =  Stock::query()
            ->select(
                'stocks.product_id',
                'stocks.store_id',
                DB::raw('sum(stocks.quantity) as quantity')
            )
            ->where('stocks.product_id', $request->product)
            ->where('stocks.store_id', session('store')['id'])
            ->groupBy('stocks.product_id', 'stocks.store_id')
            ->firstOrFail();

        $lots = Stock::query()
            ->select(
                'stocks.quantity',
                'stocks.provider_lot',
                'stocks.lot',
                'stocks.expiration_date'
            )
            ->where('stocks.product_id', $request->product)
            ->where('stocks.store_id', session('store')['id'])
            ->whereNotNull('lot')
            ->where('quantity', ">", 0)
            ->get();

        return view('stocks.show', compact('item', 'lots'));
    }
}
