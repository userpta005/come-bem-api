<?php

namespace App\Http\Controllers\API;

use App\Enums\AccountEntryType;
use App\Models\Account;
use App\Models\AccountEntry;
use App\Models\CreditPurchase;
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
            $inputs['uuid'] = (string) Uuid::uuid4();
            $inputs['amount'] = moneyToFloat($inputs['amount']);

            $creditPurchase = CreditPurchase::query()->create($inputs);

            $inputs['description'] = AccountEntryType::CREDIT->name();
            $inputs['credit_purchase_id'] = $creditPurchase->id;
            $inputs['type'] = AccountEntryType::CREDIT;

            AccountEntry::query()->create($inputs);

            DB::commit();
            return $this->sendResponse([], 'Recarga realizada com sucesso!', 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'amount' => ['required'],
            'payment_method_id' => ['required', Rule::exists('payment_methods', 'id')]
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
