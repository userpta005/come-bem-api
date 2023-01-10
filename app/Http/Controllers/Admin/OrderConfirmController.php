<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderConfirmController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
        $order = Order::with('orderItems.product.stock')->findOrFail($id);

        if ($order->status == OrderStatus::RETIRED) {
            return redirect()->to('home')
                ->withError("Pedido já retirado");
        }

        foreach ($order->orderItems as $item) {
            if ($item->quantity > $item->product->stock->quantity) {
                return redirect()->to('home')
                    ->withError("{$item->product->name} sem quantidade no estoque");
            }
        }

        DB::beginTransaction();
        $order->delivery_date = now();
        $order->status = OrderStatus::RETIRED;
        $order->save();
        foreach ($order->orderItems as $item) {
            if ($item->quantity > $item->product->stock->quantity) {
                Stock::where('id', $item->product->stock->id)
                    ->decrement('quantity', $item->quantity);
            }
        }
        DB::commit();

        return redirect()->to('home')
            ->withStatus("Pedido retirado com sucesso.");
    }
}
