<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\Account;
use App\Models\LimitedProduct;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class MenuController extends BaseController
{
    public function index(Request $request, Account $account)
    {
        $query = Product::query()
            ->with(['limitedProducts' => function ($query) use ($account) {
                $query->where('account_id', $account->id);
            }])
            ->where('status', Status::ACTIVE)
            ->where('store_id', $request->get('store')['id']);

        $data = $request->filled('page') ? $query->paginate(10) : $query->get();

        return $this->sendResponse($data);
    }

    public function update(Request $request, Account $account)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'products' => ['required', 'array'],
                'products.*' => [Rule::exists('products', 'id')],
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors(), 422);
        }

        try {
            DB::beginTransaction();

            foreach ($request->products as $product) {
                $product['account_id'] = $account->id;
                if (filter_var($product['is_checked'], FILTER_VALIDATE_BOOL)) {
                    LimitedProduct::query()->firstOrCreate(
                        ['product_id' => $product['product_id'], 'account_id' => $account->id],
                        $product
                    );
                } else {
                    LimitedProduct::query()
                        ->where('product_id', $product['product_id'])
                        ->where('account_id', $account->id)
                        ->delete();
                }
            }

            DB::commit();
            return $this->sendResponse([], "Produtos bloqueados atualizados com sucesso", 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    public function show(Request $request, Account $account, $id)
    {
        $item = Product::query()
            ->with(['limitedProducts' => function ($query) use ($account) {
                $query->where('account_id', $account->id);
            }])
            ->where('status', Status::ACTIVE)
            ->where('store_id', $request->get('store')['id'])
            ->findOrFail($id);

        return $this->sendResponse($item);
    }
}
