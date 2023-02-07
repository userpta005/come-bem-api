<?php

namespace App\Http\Controllers\API;

use App\Models\Cashier;
use App\Models\CashMovement;
use App\Models\MovementType;
use App\Models\OpenCashier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;
use Exception;

class OpenCashierController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:open-cashiers_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:open-cashiers_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:open-cashiers_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:open-cashiers_delete', ['only' => ['destroy']]);
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
                ->findOrFail($inputs['cashier_id']);

            if ($cashier->status != 3 && $inputs['operation'] == $cashier->status) {
                throw new Exception('Caixa já possui este status !');
            }

            if ($inputs['operation'] == 2 && $cashier->status == 1 && $cashier->user_id != auth()->id()) {
                throw new Exception('Esse caixa está em uso por outra pessoa !');
            }

            $inputs['balance'] = moneyToFloat($request->balance);
            $inputs['money_change'] = moneyToFloat($request->money_change);
            $inputs['date_operation'] = now();
            $inputs['token'] = (string) Uuid::uuid4();

            $open_cashier = OpenCashier::query()->create($inputs);

            $inputs['status'] = $inputs['operation'];

            $movementType = MovementType::query()
                ->findOrFail($inputs['operation'] == 1 ? 4 : 3);

            $inputs['movement_type_id'] = $movementType->id;
            $inputs['cashier_id'] = $cashier->id;
            $inputs['amount'] = $inputs['money_change'];

            if (!empty($inputs['amount'])) {
                CashMovement::query()->create($inputs);
            }

            if ($inputs['operation'] != 1) {
                $inputs['user_id'] =  null;
                $inputs['open_cashier_id'] = null;
            } else {
                $inputs['open_cashier_id'] = $open_cashier->id;
            }

            $cashier->fill($inputs)->save();

            if ($inputs['operation'] == 1) {
                session()->put('openedCashier', true);
                session()->put('cashier', $cashier);
            } else {
                session()->forget('openedCashier');
                session()->forget('cashier');
            }

            $msg = $inputs['operation'] == 1 ? 'Abertura de caixa realizada com sucesso !' : 'Fechamento de caixa realizado com sucesso !';

            DB::commit();

            return $this->sendResponse($cashier, $msg, 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    public function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'cashier_id' => ['required', Rule::exists('cashiers', 'id')],
            'user_id' => ['required', Rule::exists('users', 'id')],
            'operation' => ['required'],
            'balance' => ['required', 'max:10'],
            'money_change' => ['required', 'max:10'],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
