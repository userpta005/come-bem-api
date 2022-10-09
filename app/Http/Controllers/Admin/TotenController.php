<?php

namespace App\Http\Controllers\Admin;

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

    public function index(Request $request, Store $store)
    {
        $data =  Totem::query()
            ->where('store_id', $store->id)
            ->orderBy('name')->paginate(10);

        return view('totens.index', compact('data', 'store'));
    }

    public function create(Store $store)
    {
        return view('totens.create', compact('store'));
    }

    public function store(Request $request, Store $store)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();

        DB::transaction(function () use ($request, $store) {
            $inputs = $request->all();
            $inputs['store_id'] = $store->id;
            $inputs['token'] = (string) Uuid::uuid4();

            Totem::create($inputs);
        });

        return redirect()->route('stores.totens.index', [$store])
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show(Store $store, $id)
    {
        $item = Totem::findOrFail($id);

        return view('totens.show', compact('item', 'store'));
    }

    public function edit(Store $store, $id)
    {
        $item = Totem::findOrFail($id);

        return view('totens.edit', compact('item', 'store'));
    }

    public function update(Request $request, Store $store, $id)
    {
        $item = Totem::findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $item->getKey())
        )->validate();

        $item->fill($request->all())->save();

        return redirect()->route('stores.totens.index', [$store])
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy(Store $store, $id)
    {
        $item = Totem::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('stores.totens.index', [$store])
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('stores.totens.index', [$store])
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
