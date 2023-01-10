<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Common\Status;
use Illuminate\Http\Request;
use App\Models\Totem;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Ramsey\Uuid\Uuid;

class TotenController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:totens_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:totens_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:totens_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:totens_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data =  Totem::query()
            ->orderBy('name')
            ->paginate(10);

        return view('totens.index', compact('data', 'store'));
    }

    public function create()
    {
        $stores = Store::person()
            ->where('stores.status', Status::ACTIVE)
            ->get();

        return view('totens.create', compact('store', 'stores'));
    }

    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();

        DB::transaction(function () use ($request) {
            $inputs = $request->all();
            $inputs['token'] = (string) Uuid::uuid4();

            Totem::create($inputs);
        });

        return redirect()->route('totens.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show($id)
    {
        $item = Totem::with('store.people')->findOrFail($id);

        return view('totens.show', compact('item', 'store'));
    }

    public function edit($id)
    {
        $item = Totem::findOrFail($id);

        $stores = Store::person()
            ->where('stores.status', Status::ACTIVE)
            ->get();

        return view('totens.edit', compact('item', 'stores'));
    }

    public function update(Request $request, $id)
    {
        $item = Totem::findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $item->getKey())
        )->validate();

        $item->fill($request->all())->save();

        return redirect()->route('totens.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = Totem::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('totens.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('totens.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'max:20', Rule::unique('totens', 'name')->ignore($primaryKey)],
            'status' => ['required', new Enum(\App\Enums\Common\Status::class)],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
