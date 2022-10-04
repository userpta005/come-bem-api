<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Card;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Dependent;
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

    public function index(Request $request, Dependent $dependent)
    {
        $data = Card::query()
        ->where('dependent_id', $dependent->id)
        ->orderBy('uuid')->paginate(10);

        return view('cards.index', compact('data', 'dependent'));
    }

    public function create(Dependent $dependent)
    {
        return view('cards.create', compact('dependent'));
    }

    public function store(Request $request, Dependent $dependent)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();

        $inputs = $request->all();
        $inputs['dependent_id'] = $dependent->id;

        Card::create($inputs);

        return redirect()->route('dependents.cards.index', ['dependent' => $dependent])
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show(Dependent $dependent, $id)
    {
        $item = Card::findOrFail($id);

        return view('cards.show', compact('item', 'dependent'));
    }

    public function edit(Dependent $dependent, $id)
    {
        $item = Card::findOrFail($id);

        return view('cards.edit', compact('item', 'dependent'));
    }

    public function update(Request $request, Dependent $dependent, $id)
    {
        $item = Card::findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $item->getKey())
        )->validate();


        $item->fill($request->all())->save();

        return redirect()->route('dependents.cards.index', ['dependent' => $dependent])
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy(Dependent $dependent, $id)
    {
        $item = Card::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('dependents.cards.index', ['dependent' => $dependent])
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('dependents.cards.index', ['dependent' => $dependent])
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
