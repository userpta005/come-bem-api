<?php

namespace App\Http\Controllers\API;

use App\Enums\OrderStatus;
use App\Models\Account;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemsController extends BaseController
{
    public function index(Request $request, $id)
    {
        $account = Account::query()->findOrFail($id);

        $storeId = $request->get('store')['id'];

        if ($account->store->id != $storeId) {
            return $this->sendError('Conta não cadastrada nessa loja.', [], 403);
        }

        $orderItems = OrderItem::query()
            ->with('product.um')
            ->whereDate('date', '>=', today()->subDays(30))
            ->whereHas('order', function ($query) use ($account) {
                $query->where('status', OrderStatus::RETIRED)
                    ->whereHas('account', fn ($query) => $query->where('id', $account->id));
            })
            ->get();

        return $this->sendResponse($orderItems);
    }
}
