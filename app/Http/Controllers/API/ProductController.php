<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\Account;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function index(Request $request, $id = null)
    {
        $account = Account::query()->find($id);

        $query = Product::query()
            ->with([
                'limitedProducts' => function ($query) use ($account) {
                    $query->when(!empty($account), fn ($query) => $query->where('account_id', $account->id));
                },
                'stock'
            ])
            ->where('status', Status::ACTIVE)
            ->where('store_id', $request->get('store')['id']);

        $data = $request->filled('page') ? $query->paginate(10) : $query->get();

        return $this->sendResponse($data);
    }
}
