<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Common\Status;
use App\Enums\StoreStatus;
use App\Models\{
    Store,
    Valuestore,
    Stock,
    Product,
    StockMovement,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DevolutionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:devolutions_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:devolutions_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:devolutions_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:devolutions_delete', ['only' => ['destroy']]);
        $this->middleware('store');
    }

    public function index(Request $request)
    {
        $products = Product::query()
            ->where('status', Status::ACTIVE)
            ->where('store_id', session('store')['id'])
            ->orderBy('name')
            ->get();

        $data =  StockMovement::query()
            ->with([
                'user.people',
                'stock.product.um',
                'stock.store',
            ])
            ->where('type', StockMovement::DEVOLUTION)
            ->whereHas('stock', function ($query) use ($request) {
                $query->where('store_id', session('store')['id'])
                    ->when(!empty($request->product_id), function ($query) use ($request) {
                        $query->where('product_id', $request->product_id);
                    });
            })
            ->when(!empty($request->start_date), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->start_date);
            })
            ->when(!empty($request->end_date), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->end_date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('devolutions.index', compact('data', 'products'));
    }

    public function create(Valuestore $settings)
    {
        if ($settings->has('mve')) {
            $count = $settings->get('mve');
            $settings->put('mve', $count += 1);
        } else {
            $settings->put('mve', 1);
        }

        $stores = Store::query()
            ->person()
            ->where('stores.status', StoreStatus::ENABLED)
            ->where('stores.id', session('store')['id'])
            ->orderBy('people.name')
            ->get();

        return view('devolutions.create', compact('stores'));
    }

    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();

        DB::transaction(function () use ($request) {
            for ($i = 0; $i < count($request->stock_id); $i++) {
                StockMovement::query()
                    ->create(
                        [
                            'code' => $request->code,
                            'user_id' => auth()->id(),
                            'type' => $request->type,
                            'stock_id' => $request->stock_id[$i],
                            'quantity' => moneyToFloat($request->quantity[$i]),
                        ]
                    );

                Stock::query()
                    ->where('id', $request->stock_id[$i])
                    ->increment('quantity', moneyToFloat($request->quantity[$i]));
            }
        });

        return redirect()->route('devolutions.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show($id)
    {
        $item = StockMovement::query()
            ->with(['user.people', 'stock.product', 'stock.store'])
            ->findOrFail($id);

        return view('devolutions.show', compact('item'));
    }

    public function destroy($id)
    {
        $item = StockMovement::findOrFail($id);

        DB::beginTransaction();
        try {
            Stock::where('id', $item->stock_id)->decrement('quantity', $item->quantity);
            $item->delete();
            DB::commit();
            return redirect()->route('devolutions.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('devolutions.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'code' => ['required'],
            'stock_id.*' => ['required', Rule::exists('stocks', 'id')],
            'quantity.*' => ['required'],
            'store_id' => ['required', Rule::exists('stores', 'id')],
            'type' => ['required']
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
