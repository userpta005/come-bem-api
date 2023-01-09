<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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
        $order = Order::findOrFail($id);

        if ($order->status == OrderStatus::RETIRED) {
            return redirect()->to('home')
                ->withError( "Pedido já retirado");
        }

        $order->delivery_date = now();
        $order->status = OrderStatus::RETIRED;
        $order->save();

        return redirect()->to('home')
            ->withStatus("Pedido retirado com sucesso.");
    }
}
