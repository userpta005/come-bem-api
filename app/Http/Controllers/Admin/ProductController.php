<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Common\Active;
use App\Enums\NutritionalClassification;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:products_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:products_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:products_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:products_delete', ['only' => ['destroy']]);
        $this->middleware('store');
    }

    public function index(Request $request)
    {
        $data = Product::where('store_id', session('store')['id'])->paginate(10);

        return view('products.index', compact('data'));
    }

    public function create()
    {
        $sections = Section::where('is_enabled', true)->get();

        return view('products.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();

        DB::transaction(function () use ($request) {
            $inputs = $request->all();
            $inputs['store_id'] = session('store')['id'];

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $upload = $request->file('image')->store('products', 'public');
                $inputs['image'] = $upload;
            }
            Product::create($inputs);
        });

        return redirect()->route('products.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show($id)
    {
        $item = Product::findOrFail($id);

        return view('products.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Product::findOrFail($id);
        $sections = Section::where('is_enabled', true)->get();

        return view('products.edit', compact('item', 'sections'));
    }

    public function update(Request $request, $id)
    {
        $item = Product::findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $item->getKey())
        )->validate();

        DB::transaction(function () use ($item, $request) {
            $inputs = $request->all();

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $upload = $request->file('image')->store('products', 'public');
                if ($item->image) {
                    Storage::disk('public')->delete($item->image);
                }

                $inputs['image'] = $upload;
            }
            $item->fill($inputs)->save();
        });

        return redirect()->route('products.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = Product::findOrFail($id);

        try {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $item->delete();
            return redirect()->route('products.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'name' => ['required', 'string', 'max:40', Rule::unique('products')->ignore($primaryKey)],
            'section_id' => ['required', Rule::exists('sections', 'id')],
            'ncm_id' => ['required', Rule::exists('ncms', 'id')],
            'nutritional_classification' => ['required', new Enum(NutritionalClassification::class)],
            'is_active' => ['required', new Enum(Active::class)],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
