<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Common\Status;
use App\Enums\StoreStatus;
use App\Models\{
    Product,
    Store,
    OpeningBalance,
    Stock,
    User
};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\{Validator, DB};

class OpeningBalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:openingbalances_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:openingbalances_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:openingbalances_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:openingbalances_delete', ['only' => ['destroy']]);
        $this->middleware('store');
    }

    public function index(Request $request)
    {
        $products = Product::query()
            ->where('status', Status::ACTIVE)
            ->where('store_id', session('store')['id'])
            ->orderBy('name')
            ->get();

        $data = OpeningBalance::query()
            ->with('product.um')
            ->when(!empty($request->product_id), function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })
            ->where('store_id', session('store')['id'])
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('openingbalances.index', compact(
            'data',
            'products'
        ));
    }

    public function create()
    {
        $products = Product::query()
            ->where('status', Status::ACTIVE)
            ->where('store_id', session('store')['id'])
            ->orderBy('name')
            ->get();

        $stores = Store::query()
            ->person()
            ->where('stores.status', StoreStatus::ENABLED)
            ->where('stores.id', session('store')['id'])
            ->orderBy('people.name')
            ->get();

        return view('openingbalances.create', compact('products', 'stores'));
    }

    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            $this->rules($request, false, true),
        )->validate();

        DB::transaction(function () use ($request) {
            $inputs = $request->all();
            $inputs['quantity'] = moneyToFloat($inputs['quantity']);
            $inputs['total_amount'] = moneyToFloat($inputs['total_amount']);
            if ($request->provider_lot) {
                $inputs['lot'] = uniqid();
            }

            OpeningBalance::create($inputs);

            Stock::updateOrCreate(
                [
                    'product_id' => $request->product_id,
                    'store_id' => $request->store_id,
                    'lot' => $inputs['lot'] ?? null
                ],
                $inputs
            );
        });

        return redirect()->route('openingbalances.index')
            ->withStatus('Registro criado com sucesso.');
    }

    public function show($id)
    {
        $item = OpeningBalance::with('product.um', 'store.people')->findOrFail($id);

        return view('openingbalances.show', compact('item'));
    }

    public function edit($id)
    {
        $item = OpeningBalance::query()->findOrFail($id);

        $products = Product::query()
            ->where('status', Status::ACTIVE)
            ->where('store_id', session('store')['id'])
            ->orderBy('name')
            ->get();

        $stores = Store::query()
            ->person()
            ->where('stores.status', StoreStatus::ENABLED)
            ->where('stores.id', session('store')['id'])
            ->orderBy('people.name')
            ->get();

        return view('openingbalances.edit', compact('item', 'products', 'stores'));
    }

    public function update(Request $request, $id)
    {
        $item = OpeningBalance::query()->findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $item->getKey())
        )->validate();

        DB::transaction(function () use ($item, $request) {
            $inputs = $request->all();
            $inputs['quantity'] = moneyToFloat($inputs['quantity']);
            $inputs['total_amount'] = moneyToFloat($inputs['total_amount']);

            if ($inputs['quantity'] >= $item->quantity) {
                $quantity = $inputs['quantity'] - $item->quantity;
                $method = 'increment';
            } else {
                $quantity = $item->quantity - $inputs['quantity'];
                $method = 'decrement';
            }

            Stock::query()
                ->where([
                    'product_id' => $request->product_id,
                    'store_id' => $request->store_id,
                    'lot' => $item->lot ?? null
                ])
                ->{$method}('quantity', $quantity);

            $item->fill($inputs)->save();
        });

        return redirect()->route('openingbalances.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = OpeningBalance::query()->findOrFail($id);

        DB::beginTransaction();
        try {
            $item->delete();
            Stock::query()
                ->where([
                    'product_id' => $item->product_id,
                    'store_id' => $item->store_id,
                    'lot' => $item->lot ?? null
                ])
                ->decrement('quantity', $item->quantity);
            DB::commit();
            return redirect()->route('openingbalances.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('openingbalances.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'product_id' => ['required', Rule::exists('products', 'id')],
            'store_id' => ['required',  Rule::exists('stores', 'id')],
            'total_amount' => ['required'],
            'date' => ['required', 'date'],
            'quantity' => ['required'],
            'provider_lot' => ['nullable', 'max:15', Rule::requiredIf(function () use ($request) {
                $product = Product::find($request->input('product_id'));
                return $product->has_lot;
            })],
            'expiration_date' => ['nullable', 'date', Rule::requiredIf(function () use ($request) {
                $product = Product::find($request->input('product_id'));
                return $product->has_lot;
            })],
        ];

        $messages = [
            'provider_lot.required' => 'O produto requer um lote.',
            'expiration_date.required' => 'O produto requer uma data de validade.',
        ];

        return !$changeMessages ? $rules : $messages;
    }
}
