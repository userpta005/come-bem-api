<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cashier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CashierController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cashiers_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:cashiers_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:cashiers_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:cashiers_delete', ['only' => ['destroy']]);
        $this->middleware('store');
    }

    public function index(Request $request)
    {
        $data = Cashier::query()
            ->where('store_id', session('store')['id'])
            ->paginate(10);

        return view('cashiers.index', compact('data'));
    }

    public function create()
    {
        return view('cashiers.create');
    }

    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();

        $inputs = $request->all();

        $inputs['store_id'] = session('store')['id'];
        $inputs['balance'] = moeda($request->balance);

        Cashier::create($inputs);

        return redirect()->route('cashiers.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show($id)
    {
        $item = Cashier::query()->findOrFail($id);

        return view('cashiers.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Cashier::where('id', $id)->first();

        return view('cashiers.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Cashier::findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $id)
        )->validate();

        DB::transaction(function () use ($request, $item) {

            $inputs = $request->all();
            $inputs['balance'] = moeda($request->balance);

            $item->fill($inputs)->save();
        });

        return redirect()->route('cashiers.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = Cashier::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('cashiers.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception$e) {
            return redirect()->route('cashiers.index')
                ->withError('Registro não foi deletado.');
        }
    }

    public function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'code' => ['required', 'max:4'],
            'status' => ['required'],
            'description' => ['required', 'max:20'],
            'balance' => ['required', 'max:10'],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
