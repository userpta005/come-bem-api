<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\Account;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LimitedProductController extends BaseController
{
    public function update(Request $request, $id)
    {
        $account = Account::query()->findOrFail($id);

        $storeId = $request->get('store')['id'];

        if ($account->store->id != $storeId) {
            return $this->sendError('Conta não cadastrada nessa loja.', [], 403);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'products' => ['array'],
                'products.*.id' => [Rule::exists('products', 'id')]
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors(), 422);
        }

        try {
            DB::beginTransaction();

            $products = $request->exists('products') ? $request->get('products') : [];
            $account->limitedProducts()->sync($products);

            $user = User::getAllDataUser();

            DB::commit();
            return $this->sendResponse($user, 'Atualização realizada com sucesso', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage(), 500);
        }
    }
}
