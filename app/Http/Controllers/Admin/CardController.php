<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Card;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class CardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cards_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:cards_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:cards_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:cards_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request, Account $account)
    {
        $data = Card::query()
        ->where('account_id', $account->id)
        ->orderBy('uuid')->paginate(10);

        return view('cards.index', compact('data', 'account'));
    }

    public function create(Account $account)
    {
        return view('cards.create', compact('account'));
    }

    public function store(Request $request, Account $account)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();

        $inputs = $request->all();
        $inputs['account_id'] = $account->id;

        Card::create($inputs);

        return redirect()->route('accounts.cards.index', ['account' => $account])
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show(Account $account, $id)
    {
        $item = Card::findOrFail($id);

        return view('cards.show', compact('item', 'account'));
    }

    public function edit(Account $account, $id)
    {
        $item = Card::findOrFail($id);

        return view('cards.edit', compact('item', 'account'));
    }

    public function update(Request $request, Account $account, $id)
    {
        $item = Card::findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $item->getKey())
        )->validate();


        $item->fill($request->all())->save();

        return redirect()->route('accounts.cards.index', ['account' => $account])
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy(Account $account, $id)
    {
        $item = Card::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('accounts.cards.index', ['account' => $account])
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('accounts.cards.index', ['account' => $account])
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'uuid' => ['required', 'max:100', Rule::unique('cards')->ignore($primaryKey)],
            'status' => ['required', new Enum(\App\Enums\Common\Status::class)],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
