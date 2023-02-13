<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class ChangeStoreSessionController extends Controller
{
    public function __invoke($id)
    {
        foreach (session('stores') as $store) {
            if ($store['id'] == $id) {
                session(['store' => $store]);
            }
        }

        $user = User::query()
            ->with('cashier')
            ->findOrFail(auth()->id());

        if (
            session()->exists('store')
            && $user->cashier()->exists()
            && $user->cashier->store_open_cashier == session('store')['id']
        ) {
            session()->put('openedCashier', true);
            session()->put('cashier', $user->cashier);
        } else {
            session()->forget('openedCashier');
            session()->forget('cashier');
        }

        return redirect()->back()
            ->withStatus('Loja alterada com sucesso.');
    }
}
