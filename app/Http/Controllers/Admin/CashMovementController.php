<?php

namespace App\Http\Controllers\Admin;

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

class CashMovementController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cash-movements_create', ['only' => ['create', 'store', 'painelStore']]);
        $this->middleware('permission:cash-movements_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:cash-movements_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:cash-movements_delete', ['only' => ['destroy']]);
        $this->middleware('store');
    }

    public function index(Request $request)
    {
        $data = CashMovement::query()
            ->where('store_id', session('store')['id'])
            ->when(!empty($request->cashier_id), function ($query) use ($request) {
                $query->where('cashier_id', $request->cashier_id);
            })
            ->when(!empty($request->start_date), function ($query) use ($request) {
                $query->whereDate('date_operation', '>=', $request->start_date);
            })
            ->when(!empty($request->end_date), function ($query) use ($request) {
                $query->whereDate('date_operation', '<=', $request->end_date);
            })
            ->orderBy('date_operation', 'desc')
            ->paginate(10);

        $cashiers = Cashier::query()
            ->where('store_id', session('store')['id'])
            ->get();

        return view('cash-movements.index', compact('data', 'cashiers'));
    }

    public function create()
    {
        $data = CashMovement::query()
            ->where('store_id', session('store')['id'])
            ->get();

        $cashiers = Cashier::query()
            ->where('store_id', session('store')['id'])
            ->where('status', true)
            ->get();

        $clients = Client::query()
            ->person()
            ->with(['dependents.accounts' => function ($query) {
                $query->where('store_id', session('store')['id']);
            }])
            ->whereHas('dependents', function ($query) {
                $query->where('status', Status::ACTIVE)
                    ->whereHas('accounts', function ($query) {
                        $query->where('store_id', session('store')['id']);
                    });
            })
            ->get();

        $payment_methods = PaymentMethod::query()
            ->where('status', Status::ACTIVE)
            ->get();

        $movement_types = MovementType::query()
            ->orderBy('name', 'asc')
            ->get();

        return view('cash-movements.create', compact(
            'data', 'cashiers', 'movement_types',
            'clients', 'payment_methods'));
    }

    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();

        $cashier = Cashier::query()
            ->where('store_id', session('store')['id'])
            ->where('status', true)
            ->where('id', $request->cashier_id)
            ->first();

        if (!isset($cashier)) {
            return redirect()->route('cash-movements.index')
                ->withError('Registro não pode ser adicionado.');
        }

        DB::transaction(function () use ($request, $cashier) {
            $inputs = $request->all();
            $inputs['store_id'] = session('store')['id'];
            $inputs['amount'] = moeda($request->amount);
            $inputs['date_operation'] = now();
            $inputs['token'] = (string) Uuid::uuid4();

            $cash_movement = CashMovement::create($inputs);

            $cash_movement->open_cashier_id = $cashier->open_cashier_id;
            $cash_movement->save();
        });

        return redirect()->route('cash-movements.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function painelStore(Request $request)
    {
        $this->store($request);

        return redirect()->route('home')
            ->withStatus('Movimento de caixa adicionado com sucesso.');
    }

    public function show($id)
    {
        $item = CashMovement::query()->findOrFail($id);

        return view('cash-movements.show', compact('item'));
    }

    public function edit($id)
    {
        $item = CashMovement::where('id', $id)->first();

        $cashiers = Cashier::query()
            ->where('store_id', session('store')['id'])
            ->where('status', true)
            ->get();

        $clients = Client::query()
            ->person()
            ->with(['dependents.accounts' => function ($query) {
                $query->where('store_id', session('store')['id']);
            }])
            ->whereHas('dependents', function ($query) {
                $query->where('status', Status::ACTIVE)
                    ->whereHas('accounts', function ($query) {
                        $query->where('store_id', session('store')['id']);
                    });
            })
            ->get();

        $payment_methods = PaymentMethod::query()
            ->where('status', Status::ACTIVE)
            ->get();

        $movement_types = MovementType::query()
            ->orderBy('name', 'asc')
            ->get();

        return view('cash-movements.edit', compact(
            'item', 'cashiers', 'clients',
            'payment_methods', 'movement_types'));
    }

    public function update(Request $request, $id)
    {

        Validator::make(
            $request->all(),
            $this->rules($request, $id)
        )->validate();

        $cashier = Cashier::query()
            ->where('store_id', session('store')['id'])
            ->where('status', true)
            ->where('id', $request->cashier_id)
            ->first();

        if (!isset($cashier)) {
            return redirect()->route('cash-movements.index')
                ->withError('Registro não pode ser atualizado.');
        }

        DB::transaction(function () use ($request, $id, $cashier) {

            $item = CashMovement::findOrFail($id);
            $inputs = $request->all();

            $inputs['amount'] = moeda($request->amount);
            $item->fill($inputs)->save();

            $item->open_cashier_id = $cashier->open_cashier_id;
            $item->save();
        });

        return redirect()->route('cash-movements.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = CashMovement::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('cash-movements.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception$e) {
            return redirect()->route('cash-movements.index')
                ->withError('Registro não foi deletado.');
        }
    }

    public function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'cashier_id' => ['required', Rule::exists('cashiers', 'id')],
            'movement_type_id' => ['required', Rule::exists('movement_types', 'id')],
            'client_id' => ['nullable', Rule::exists('clients', 'id')],
            'payment_method_id' => ['required', Rule::exists('payment_methods', 'id')],
            'amount' => ['required', 'max:10'],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
