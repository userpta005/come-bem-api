<?php

namespace App\Http\Controllers\Admin;

use App\Models\MovementType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MovementTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:movement-types_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:movement-types_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:movement-types_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:movement-types_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = MovementType::query()
            ->orderBy('type', 'asc')
            ->paginate(10);

        return view('movement-types.index', compact('data'));
    }

    public function create()
    {
        return view('movement-types.create');
    }

    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();

        $inputs = $request->all();

        MovementType::create($inputs);

        return redirect()->route('movement-types.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show($id)
    {
        $item = MovementType::query()->findOrFail($id);

        return view('movement-types.show', compact('item'));
    }

    public function edit($id)
    {
        $item = MovementType::where('id', $id)->first();

        return view('movement-types.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = MovementType::findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $id)
        )->validate();

        DB::transaction(function () use ($request, $item) {

            $inputs = $request->all();

            $item->fill($inputs)->save();
        });

        return redirect()->route('movement-types.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = MovementType::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('movement-types.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception$e) {
            return redirect()->route('movement-types.index')
                ->withError('Registro não foi deletado.');
        }
    }

    public function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'type' => ['required', 'max:30'],
            'class' => ['required']
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
