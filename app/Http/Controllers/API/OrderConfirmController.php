<?php

namespace App\Http\Controllers\API;

use App\Enums\AccountEntryType;
use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderConfirmController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
        $order = Order::with('account', 'orderItems.product.stock')->findOrFail($id);

        if ($order->status == OrderStatus::RETIRED) {
            return $this->sendError("Pedido já retirado", [], 403);
        }

        foreach ($order->orderItems as $item) {
            if ($item->quantity > $item->product->stock->quantity) {
                return $this->sendError("{$item->product->name} sem quantidade no estoque", [], 403);
            }
        }

        DB::beginTransaction();
        $order->delivery_date = now();
        $order->status = OrderStatus::RETIRED;
        $order->save();
        foreach ($order->orderItems as $item) {
            $item->product->stock()->decrement('quantity', $item->quantity);
        }

        $entry = [
            'description' => AccountEntryType::CONSUMPTION->name(),
            'amount' => $order->amount,
            'type' => AccountEntryType::CONSUMPTION
        ];
        $order->account->accountEntries()->create($entry);

        DB::commit();

        return $this->sendResponse([], 'Pedido retirado com sucesso.', 200);
    }
}
