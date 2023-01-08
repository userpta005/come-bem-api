<?php

namespace App\Http\Controllers\API;

use App\Enums\AccountTurn;
use App\Models\Account;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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

            $inputs = $request->all();

            $account = Account::query()->findOrFail($id);

            $storeId = $request->get('store')['id'];

            if ($account->store->id != $storeId) {
                return $this->sendError('Conta não cadastrada nessa loja.', [], 403);
            }

            $orderExists = Order::query()
                ->where([
                    ['account_id', '=', $account->id],
                    ['turn', '=', $inputs['turn']]
                ])
                ->whereDate('date', $inputs['date'])
                ->first();

            if ($orderExists) {
                return $this->sendError('Já existe um pedido para este período', [], 403);
            }

            $inputs['account_id'] = $account->id;
            $inputs['amount'] = 0;
            $productValues = [];

            foreach ($inputs['products'] as $index => $value) {
                $product = Product::query()->findOrFail($value['id']);
                $productValues[$index]['product_id'] = $product->id;
                $productValues[$index]['price'] = $product->price;
                $productValues[$index]['quantity'] = $value['quantity'];
                $productValues[$index]['total'] = $value['quantity'] * $product->price;
                $inputs['amount'] += $productValues[$index]['total'];
            }

            if ($account->day_balance < $inputs['amount']) {
                return $this->sendError('Saldo do dia insuficiente.', [], 403);
            }

            if ($account->balance < $inputs['amount']) {
                return $this->sendError('Você não tem saldo suficiente.', [], 403);
            }

            $order = Order::query()->create($inputs);

            foreach ($productValues as $item) {
                $order->orderItems()->create($item);
            }

            $account->decrement('balance', $inputs['amount']);

            $user = User::getAllDataUser();

            DB::commit();
            return $this->sendResponse($user, 'Pedido realizado com sucesso!', 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $order = Order::query()->findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            $this->rules($request, $order->id)
        );

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors(), 422);
        }

        try {
            DB::beginTransaction();

            $inputs = $request->all();

            $storeId = $request->get('store')['id'];

            if ($order->account->store->id != $storeId) {
                return $this->sendError('Conta não cadastrada nessa loja.', [], 403);
            }

            $orderExists = Order::query()
                ->where([
                    ['id', '!=', $order->id],
                    ['account_id', '=', $order->account->id],
                    ['turn', '=', $inputs['turn']]
                ])
                ->whereDate('date', $inputs['date'])
                ->first();

            if ($orderExists) {
                return $this->sendError('Já existe um pedido para este período', [], 403);
            }

            $order->account->increment('balance', $order->amount);
            $order->orderItems()->delete();
            $inputs['amount'] = 0;
            $productValues = [];

            foreach ($inputs['products'] as $index => $value) {
                $product = Product::query()->findOrFail($value['id']);
                $productValues[$index]['product_id'] = $product->id;
                $productValues[$index]['price'] = $product->price;
                $productValues[$index]['quantity'] = $value['quantity'];
                $productValues[$index]['total'] = $value['quantity'] * $product->price;
                $inputs['amount'] += $productValues[$index]['total'];
            }

            if ($order->account->day_balance < $inputs['amount']) {
                return $this->sendError('Saldo do dia insuficiente.', [], 403);
            }

            if ($order->account->balance < $inputs['amount']) {
                return $this->sendError('Você não tem saldo suficiente.', [], 403);
            }

            $order->fill($inputs)->save();

            foreach ($productValues as $item) {
                $order->orderItems()->create($item);
            }

            $order->account->decrement('balance', $inputs['amount']);

            $user = User::getAllDataUser();

            DB::commit();
            return $this->sendResponse($user, 'Pedido atualizado com sucesso!', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = Order::query()->findOrFail($id);

        try {
            DB::beginTransaction();
            $item->account->increment('balance', $item->amount);
            $item->orderItems()->delete();
            $item->delete();
            $user = User::getAllDataUser();
            DB::commit();
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
            'turn' => ['required', new Enum(AccountTurn::class)]
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
