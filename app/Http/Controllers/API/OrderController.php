<?php

namespace App\Http\Controllers\API;

use App\Models\Account;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderController extends BaseController
{
    public function store(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules($request)
        );

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors(), 422);
        }

        try {
            DB::beginTransaction();

            $account = Account::query()->findOrFail($id);

            $storeId = $request->get('store')['id'];

            if ($account->store->id != $storeId) {
                return $this->sendError('Conta não cadastrada nessa loja.', [], 403);
            }

            $inputs = $request->all();
            $inputs['account_id'] = $account->id;
            $inputs['amount'] = 0;

            $order = Order::query()->create($inputs);
            $productValues['order_id'] = $order->id;

            foreach ($inputs['products'] as $value) {
                $product = Product::query()->findOrFail($value['id']);
                $productValues['product_id'] = $product->id;
                $productValues['price'] = $product->price;
                $productValues['quantity'] = $value['quantity'];
                $productValues['total'] = $value['quantity'] * $product->price;
                $inputs['amount'] += $productValues['total'];
                OrderItem::query()->create($productValues);
            }

            $order->fill($inputs)->save();

            $user = User::getAllDataUser();

            DB::commit();
            return $this->sendResponse($user, 'Pedido realizado com sucesso!', 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = Order::query()->findOrFail($id);

        try {
            $item->orderItems()->delete();
            $item->delete();
            $user = User::getAllDataUser();
            return $this->sendResponse($user, 'Registro deletado com sucesso.');
        } catch (\Throwable $th) {
            return $this->sendError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'products' => ['required', 'array'],
            'products.*.id' => ['required', Rule::exists('products', 'id')],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i']
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
