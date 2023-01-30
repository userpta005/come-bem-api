<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Http\Controllers\Controller;
use App\Models\Cashier;
use App\Models\CashMovement;
use App\Models\Client;
use App\Models\MovementType;
use App\Models\PaymentMethod;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class CashMovementController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:cash-movements_create', ['only' => ['create', 'store', 'painelStore']]);
        $this->middleware('permission:cash-movements_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:cash-movements_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:cash-movements_delete', ['only' => ['destroy']]);
        $this->middleware('store');
    }

    public function store(Request $request)
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
            $inputs['store_id'] = $request->get('store')['id'];

            $cashier = Cashier::query()
                ->where('store_id', $inputs['store_id'])
                ->where('status', 1)
                ->findOrFail($inputs['cashier_id']);

            $inputs['amount'] = moneyToFloat($inputs['amount']);
            $inputs['date_operation'] = now();
            $inputs['token'] = (string) Uuid::uuid4();

            CashMovement::create($inputs);

            $cashier->balance = moneyToFloat($inputs['balance']);
            $cashier->save();

            DB::commit();
            return $this->sendResponse($cashier, 'Movimento realizado com sucesso !', 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    public function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'cashier_id' => ['required', Rule::exists('cashiers', 'id')],
            'movement_type_id' => ['required', Rule::exists('movement_types', 'id')],
            'client_id' => ['nullable', Rule::exists('clients', 'id')],
            'payment_method_id' => ['nullable', Rule::exists('payment_methods', 'id')],
            'amount' => ['required', 'max:10'],
            'balance' => ['required', 'max:10'],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
