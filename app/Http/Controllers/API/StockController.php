<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends BaseController
{
    public function index(Request $request)
    {
        $data = Stock::query()
            ->select(
                'stocks.*',
                'products.name as product',
                'people.name as store',
                'measurement_units.name as um',
            )
            ->join('products', 'products.id', '=', 'stocks.product_id')
            ->join('measurement_units', 'products.um_id', '=', 'measurement_units.id')
            ->join('stores', 'stores.id', '=', 'stocks.store_id')
            ->join('people', 'people.id', '=', 'stores.person_id')
            ->when($request->filled('store'), function ($query) use ($request) {
                $query->where('stocks.store_id', $request->store);
            })
            ->when($request->filled('has-quantity'), function ($query) {
                $query->where('quantity', '>', 0);
            })
            ->get();

        return $this->sendResponse($data);
    }
}
