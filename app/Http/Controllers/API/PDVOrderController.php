<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use Ramsey\Uuid\Uuid;
use App\Models\Account;
use App\Models\Cashier;
use App\Models\Product;
use App\Enums\AccountTurn;
use App\Models\CashMovement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PDVOrderController extends BaseController
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

            $account = Account::query()
                ->with('dependent.client')
                ->findOrFail($id);

            $inputs['store_id'] = $request->get('store')['id'];

            if ($account->store->id != $inputs['store_id']) {
                return $this->sendError('Conta não cadastrada nessa loja.', [], 403);
            }

            if (empty($inputs['cashier_id']) && $account->dependent->client->type->isGENERIC_CONSUMER()) {
                return $this->sendError('Não é possível realizar uma compra para um consumidor genérico com o caixa fechado !', [], 403);
            }

            if (empty($inputs['cashier_id']) && !$account->dependent->client->type->isGENERIC_CONSUMER() && $inputs['payment_method_id'] != 5) {
                return $this->sendError('Para uma operação diferente de crédito, abra o caixa !', [], 403);
            }

            $now = now();
            $inputs['date'] = $now->format('Y-m-d H:m:s');

            if ($now->greaterThanOrEqualTo('07:00:00') && $now->lessThanOrEqualTo('12:59:59')) {
                $inputs['turn'] = AccountTurn::MORNING;
            } else if ($now->greaterThanOrEqualTo('13:00:00') && $now->lessThanOrEqualTo('17:59:59')) {
                $inputs['turn'] = AccountTurn::AFTERNOON;
            } else {
                $inputs['turn'] = AccountTurn::NOCTURNAL;
            }

            $inputs['account_id'] = $account->id;
            $inputs['amount'] = 0;
            $productValues = [];

            foreach ($inputs['products'] as $index => $value) {
                $product = Product::query()->findOrFail($value['id']);
                $productValues[$index]['date'] = $inputs['date'];
                $productValues[$index]['product_id'] = $product->id;
                $productValues[$index]['price'] = $product->price;
                $productValues[$index]['quantity'] = $value['quantity'];
                $productValues[$index]['total'] = $value['quantity'] * $product->price;
                $inputs['amount'] += $productValues[$index]['total'];
            }

            if ($inputs['payment_method_id'] == 5 && $account->dependent->client->type != 3) {
                if (!empty(floatval($account->daily_limit)) && $account->dayBalanceByDate($inputs['date']) < $inputs['amount']) {
                    return $this->sendError('Saldo do dia insuficiente.', [], 403);
                }

                if ($account->balance < $inputs['amount']) {
                    return $this->sendError('Você não tem saldo suficiente.', [], 403);
                }
            }

            $order = Order::query()->create($inputs);

            foreach ($productValues as $item) {
                $order->orderItems()->create($item);
            }

            if ($inputs['payment_method_id'] == 5 && $account->dependent->client->type != 3) {
                $account->decrement('balance', $inputs['amount']);
            }

            $cashier = Cashier::query()
                ->where('store_id', $inputs['store_id'])
                ->where('status', 1)
                ->find($inputs['cashier_id']);

            $inputs['amount'] = $inputs['amount'];
            $inputs['date_operation'] = $now;
            $inputs['token'] = (string) Uuid::uuid4();
            $inputs['client_id'] = $account->dependent->id;
            $inputs['movement_type_id'] = 1;
            $inputs['open_cashier_id'] = !empty($cashier) ? $cashier->open_cashier_id : null;

            CashMovement::create($inputs);

            if ($inputs['payment_method_id'] == 1) {
                $cashier->increment('balance', $inputs['amount']);
                $cashier->save();
            }

            DB::commit();
            return $this->sendResponse([], 'Pedido realizado com sucesso!', 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'cashier_id' => ['nullable', Rule::exists('cashiers', 'id')],
            'payment_method_id' => ['required', Rule::exists('payment_methods', 'id')],
            'products' => ['required', 'array'],
            'products.*.id' => ['required', Rule::exists('products', 'id')],
            'products.*.quantity' => ['required', 'integer', 'min:1']
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
