<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Dependent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:accounts_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:accounts_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:accounts_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:accounts_delete', ['only' => ['destroy']]);
        $this->middleware('store');
    }

    public function index(Request $request, Dependent $dependent)
    {
        $data = Account::query()
            ->where('dependent_id', $dependent->id)
            ->where('store_id', session('store')['id'])
            ->paginate(10);

        return view('accounts.index', compact('data', 'dependent'));
    }

    public function create(Dependent $dependent)
    {
        return view('accounts.create', compact('dependent'));
    }

    public function store(Request $request, Dependent $dependent)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();

        DB::transaction(function () use ($request, $dependent) {
            $inputs = $request->all();
            $inputs['balance'] = moneyToFloat($inputs['balance']);
            $inputs['daily_limit'] = moneyToFloat($inputs['daily_limit']);
            $inputs['dependent_id'] = $dependent->id;
            $inputs['store_id'] = session('store')['id'];

            Account::create($inputs);
        });

        return redirect()->route('clients.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show(Dependent $dependent, $id)
    {
        $item = Account::findOrFail($id);

        return view('accounts.show', compact('item', 'dependent'));
    }

    public function edit(Dependent $dependent, $id)
    {
        $item = Account::findOrFail($id);

        return view('accounts.edit', compact('item', 'dependent'));
    }

    public function update(Request $request, Dependent $dependent, $id)
    {
        $item = Account::findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $item->getKey())
        )->validate();

        DB::transaction(function () use ($request, $item) {
            $inputs = $request->all();
            $inputs['balance'] = moneyToFloat($inputs['balance']);
            $inputs['daily_limit'] = moneyToFloat($inputs['daily_limit']);
            $item->fill($inputs)->save();
        });

        return redirect()->route('clients.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy(Dependent $dependent, $id)
    {
        $item = Account::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('clients.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('clients.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'balance' => ['nullable', 'max:14'],
            'daily_limit' => ['nullable', 'max:14'],
            'class' => ['nullable', 'max:10'],
            'school_year' => ['nullable', 'max:10'],
            'status' => ['required', new Enum(\App\Enums\Common\Status::class)],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
