<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Common\Status;
use App\Enums\NutritionalClassification;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\MeasurementUnit;
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
    }

    public function index(Request $request)
    {
        $sections = Section::query()
            ->where('status', Status::ACTIVE)
            ->when(session()->has('store'), fn ($query) => $query->where('store_id', session('store')['id']))
            ->when(!session()->has('store'), fn ($query) => $query->whereNull('store_id'))
            ->get();

        $classifications = NutritionalClassification::all();

        $status = Status::all();

        $data = Product::query()
            ->when(session()->has('store'), fn ($query) => $query->where('store_id', session('store')['id']))
            ->when(!session()->has('store'), fn ($query) => $query->whereNull('store_id'))
            ->when($request->filled('section'), fn ($query) => $query->where('section_id', $request->section))
            ->when($request->filled('nutritional_classification'), fn ($query) => $query->where('nutritional_classification', $request->nutritional_classification))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', "%{$request->search}%"))
            ->paginate(10);

        return view('products.index', compact('data', 'sections', 'classifications', 'status'));
    }

    public function create()
    {
        $sections = Section::query()
            ->where('status', Status::ACTIVE)
            ->when(session()->has('store'), fn ($query) => $query->where('store_id', session('store')['id']))
            ->when(!session()->has('store'), fn ($query) => $query->whereNull('store_id'))
            ->get();

        $ums = MeasurementUnit::query()
            ->where('status', Status::ACTIVE)
            ->get();

        return view('products.create', compact('sections', 'ums'));
    }

    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();

        DB::transaction(function () use ($request) {
            $inputs = $request->all();
            $inputs['price'] = moneyToFloat($inputs['price']);
            $inputs['promotion_price'] = moneyToFloat($inputs['promotion_price']);
            $inputs['store_id'] = session()->has('store') ? session('store')['id'] : null;

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
        $sections = Section::query()
            ->where('status', Status::ACTIVE)
            ->when(session()->has('store'), fn ($query) => $query->where('store_id', session('store')['id']))
            ->when(!session()->has('store'), fn ($query) => $query->whereNull('store_id'))
            ->get();
        $ums = MeasurementUnit::where('status', Status::ACTIVE)->get();

        return view('products.edit', compact('item', 'sections', 'ums'));
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
            $inputs['price'] = moneyToFloat($inputs['price']);
            $inputs['promotion_price'] = moneyToFloat($inputs['promotion_price']);

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
            'name' => ['required', 'max:40'],
            'section_id' => ['required', Rule::exists('sections', 'id')],
            'ncm_id' => ['nullable', Rule::exists('ncms', 'id')],
            'um_id' => ['required', Rule::exists('measurement_units', 'id')],
            'nutritional_classification' => ['required', new Enum(NutritionalClassification::class)],
            'status' => ['required', new Enum(Status::class)],
            'price' => ['required'],
            'promotion_price' => ['required'],
            'note' => ['nullable', 'max:200']
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
