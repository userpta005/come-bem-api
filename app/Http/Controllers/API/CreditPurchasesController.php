<?php

namespace App\Http\Controllers\API;

use App\Actions\PixPaymentAction;
use App\Actions\SplitPaymentAction;
use App\Enums\AccountEntryType;
use App\Models\Account;
use App\Models\AccountEntry;
use App\Models\Cashier;
use App\Models\CashMovement;
use App\Models\CreditPurchase;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class CreditPurchasesController extends BaseController
{
    public function index(Request $request, $id)
    {
        $account = Account::query()->findOrFail($id);

        $storeId = $request->get('store')['id'];

        if ($account->store->id != $storeId) {
            return $this->sendError('Conta não cadastrada nessa loja.', [], 403);
        }

        $account->load(['accountEntries' => fn ($query) => $query->whereDate('created_at', '>=', today()->subDays(30))]);

        return $this->sendResponse($account->accountEntries);
    }

    public function store(Request $request, $id, PixPaymentAction $pixAction, SplitPaymentAction $splitAction)
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

            $inputs = $request->all();

            $inputs['store_id'] = $request->get('store')['id'];

            if ($account->store->id != $inputs['store_id']) {
                return $this->sendError('Conta não cadastrada nessa loja.', [], 403);
            }

            $paymentMethod = PaymentMethod::find($request->payment_method_id);

            $inputs['account_id'] = $account->id;
            $inputs['uuid'] = (string) Uuid::uuid4();
            $inputs['amount'] = moneyToFloat($inputs['amount']);

            if ($paymentMethod->code == PaymentMethod::PIX) {
                $pagseguro = $request->get('store')['tenant']['pagseguro'];

                if (!isset($pagseguro)) {
                    return $this->sendError("Pagamento não configurado para esse contratante.", [], 403);
                }

                $token = $pagseguro['token'];

                $payload = [
                    'value' => moneyToFloat($inputs['amount']),
                    'reference' => $inputs['uuid'],
                    'store' => $inputs['store_id'],
                ];
                $checkout = $pixAction->execute($token, $payload);
                $inputs['checkout'] = $checkout;
            } else if ($paymentMethod->code == PaymentMethod::CREDIT_CARD) {
                $pagseguro = $request->get('store')['tenant']['pagseguro'];

                if (!isset($pagseguro)) {
                    return $this->sendError("Pagamento não configurado para esse contratante.", [], 403);
                }


                $payload = [
                    'value' => moneyToFloat($inputs['amount']),
                    'reference' => $inputs['uuid'],
                    'card' => $inputs['card'],
                    'account' => $pagseguro['account_id']
                ];

                $checkout = $splitAction->execute($payload);
                $inputs['checkout'] = $checkout;
            }

            $creditPurchase = CreditPurchase::query()->create($inputs);

            if (!empty($inputs['cashier_id'])) {
                if ($paymentMethod->code == PaymentMethod::MONEY) {
                    $inputs['description'] = AccountEntryType::CREDIT->name();
                    $inputs['credit_purchase_id'] = $creditPurchase->id;
                    $inputs['type'] = AccountEntryType::CREDIT;

                    AccountEntry::query()->create($inputs);
                    $account->increment('balance', $inputs['amount']);
                    $account->save();
                }

                $cashier = Cashier::query()
                    ->where('store_id', $inputs['store_id'])
                    ->where('status', 1)
                    ->findOrFail($inputs['cashier_id']);

                $inputs['date_operation'] = now();
                $inputs['token'] = $inputs['uuid'];
                $inputs['movement_type_id'] = 1;
                $inputs['client_id'] = $account->dependent_id;

                CashMovement::create($inputs);

                $cashier->increment('balance', $inputs['amount']);
                $cashier->save();
            }

            DB::commit();
            return $this->sendResponse($creditPurchase, 'Recarga realizada com sucesso!', 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        $rules = [
            'cashier_id' => ['nullable', Rule::exists('cashiers', 'id')],
            'amount' => ['required'],
            'payment_method_id' => ['required', Rule::exists('payment_methods', 'id')],
            'card.number' => [Rule::requiredIf($paymentMethod->code == PaymentMethod::CREDIT_CARD)],
            'card.exp_month' => [Rule::requiredIf($paymentMethod->code == PaymentMethod::CREDIT_CARD), 'min:2', 'max:2'],
            'card.exp_year' => [Rule::requiredIf($paymentMethod->code == PaymentMethod::CREDIT_CARD), 'min:4', 'max:4'],
            'card.security_code' => [Rule::requiredIf($paymentMethod->code == PaymentMethod::CREDIT_CARD), 'min:3', 'max:3'],
            'card.holder' => [Rule::requiredIf($paymentMethod->code == PaymentMethod::CREDIT_CARD), 'string'],
            'card.installments' => [Rule::requiredIf($paymentMethod->code == PaymentMethod::CREDIT_CARD), 'integer']
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
