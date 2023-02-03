<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cashier;
use App\Models\OpenCashier;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class OpenCashierController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:open-cashiers_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:open-cashiers_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:open-cashiers_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:open-cashiers_delete', ['only' => ['destroy']]);
        $this->middleware('store');
    }

    public function index(Request $request)
    {
        $data = OpenCashier::query()
            ->where('store_id', session('store')['id'])
            ->when(!empty($request->cashier_id), function ($query) use ($request) {
                $query->where('cashier_id', $request->cashier_id);
            })
            ->when(!empty($request->user_id), function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
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

        $users = User::query()
            ->get();

        return view('open-cashiers.index', compact('data', 'cashiers', 'users'));
    }

    public function create()
    {
        $data = OpenCashier::query()
            ->where('store_id', session('store')['id'])
            ->get();

        $cashiers = Cashier::query()
            ->where('store_id', session('store')['id'])
            ->where('status', "!=", 3)
            ->get();

        $users = User::query()
            ->get();

        return view('open-cashiers.create', compact('data', 'cashiers', 'users'));
    }

    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();

        $inputs = $request->all();
        $inputs['store_id'] = session('store')['id'];

        $cashier = Cashier::query()
            ->where('store_id', session('store')['id'])
            ->where('id', $request->cashier_id)
            ->first();

        if ($request->operation == $cashier->status) {
            return redirect()->route('open-cashiers.index')
                ->withError('Caixa já possui este status.');
        }

        DB::transaction(function () use ($request, $inputs, $cashier) {

            $inputs['balance'] = moeda($request->balance);
            $inputs['date_operation'] = now();
            $inputs['token'] = (string) Uuid::uuid4();

            $open_cashier = OpenCashier::create($inputs);

            $cashier->open_cashier_id = null;

            if ($inputs['operation'] == 1) {

                $cashier->status = 1;
                $cashier->open_cashier_id = $open_cashier->id;

            } else if ($inputs['operation'] == 2) {

                $cashier->status = 2;
                $cashier->balance += $inputs['balance'];

            } else {
                $cashier->status = 3;
            }

            $cashier->save();
        });

        return redirect()->route('open-cashiers.index')
            ->withStatus('Registro adicionado com sucesso.');

    }

    public function show($id)
    {
        $item = OpenCashier::query()->findOrFail($id);

        return view('open-cashiers.show', compact('item'));
    }

    public function edit($id)
    {
        $item = OpenCashier::where('id', $id)->first();

        $cashiers = Cashier::query()
            ->where('store_id', session('store')['id'])
            ->where('status', "!=", 3)
            ->get();

        $users = User::query()
            ->get();

        return view('open-cashiers.edit', compact('item', 'cashiers', 'users'));
    }

    public function update(Request $request, $id)
    {
        $item = OpenCashier::findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $id)
        )->validate();

        DB::transaction(function () use ($request, $item) {

            $inputs = $request->all();
            $inputs['balance'] = moeda($request->balance);

            $item->fill($inputs)->save();

            $cashier = Cashier::query()
                ->where('store_id', session('store')['id'])
                ->where('id', $request->cashier_id)
                ->first();

            $cashier->open_cashier_id = null;

            if ($inputs['operation'] == 1) {

                $cashier->status = 1;
                $cashier->open_cashier_id = $item->id;

            } else if ($inputs['operation'] == 2) {

                $cashier->status = 2;
                $cashier->balance += $inputs['balance'];

            } else {
                $cashier->status = 3;
            }

            $cashier->save();

        });

        return redirect()->route('open-cashiers.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = OpenCashier::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('open-cashiers.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception$e) {
            return redirect()->route('open-cashiers.index')
                ->withError('Registro não foi deletado.');
        }
    }

    public function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'cashier_id' => ['required', Rule::exists('cashiers', 'id')],
            'user_id' => ['required', Rule::exists('users', 'id')],
            'operation' => ['required'],
            'balance' => ['required', 'max:10'],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }

}
