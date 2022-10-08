<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Common\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\LimitedProduct;
use App\Models\Product;

class LimitedProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:products_view', ['only' => ['index']]);
    }

    public function index(Account $account, Request $request)
    {
        $data = Product::query()
            ->where('status', Status::ACTIVE)
            ->where('store_id', $account->store_id)
            ->get();

        $limitedProducts = LimitedProduct::query()
            ->where('account_id', $account->id)
            ->get();

        return view('limited_products.index', compact('data', 'account', 'limitedProducts'));
    }
}
