<?php

namespace App\Http\Controllers\API;

use App\Models\LimitedProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LimitedProductController extends BaseController
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->products as $product) {
                if (filter_var($product['is_checked'], FILTER_VALIDATE_BOOL) ) {
                    unset($product['is_checked']);
                    LimitedProduct::query()->firstOrCreate($product);
                } else {
                    LimitedProduct::query()
                    ->where('product_id', $product['product_id'])
                    ->where('account_id', $product['account_id'])
                    ->delete();
                }
            }

            DB::commit();
            return $this->sendResponse([], 'Salvo !');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError('Erro desconhecido.');
        }
    }
}
