<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Common\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LimitedProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:limited-products_view', ['only' => ['index']]);
        $this->middleware('permission:limited-products_edit', ['only' => ['update']]);
    }

    public function index(Account $account, Request $request)
    {
        $data = Product::query()
            ->with(['limitedProducts' => function ($query) use ($account) {
                $query->where('account_id', $account->id);
            }])
            ->where('status', Status::ACTIVE)
            ->where('store_id', session()->exists('store') ? session('store')['id'] : $account->store_id)
            ->get();

        return view('limited_products.index', compact('data', 'account'));
    }


    public function update(Account $account, Request $request)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();

        DB::transaction(function () use ($request, $account) {
            $products = $request->exists('products') ? $request->get('products') : [];
            $account->limitedProducts()->sync($products);
        });

        return redirect()->route('clients.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'products' => ['array'],
            'products.*' => [Rule::exists('products', 'id')]
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
