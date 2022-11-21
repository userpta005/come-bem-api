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
                if (filter_var($product['is_checked'], FILTER_VALIDATE_BOOL)) {
                    LimitedProduct::query()->firstOrCreate(
                        ['product_id' => $product['product_id'], 'account_id' => $product['account_id']],
                        $product
                    );
                } else {
                    LimitedProduct::query()
                        ->where('product_id', $product['product_id'])
                        ->where('account_id', $product['account_id'])
                        ->delete();
                }
            }

            DB::commit();
            return $this->sendResponse([], 'Registro salvo com sucesso', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage(), 500);
        }
    }
}
