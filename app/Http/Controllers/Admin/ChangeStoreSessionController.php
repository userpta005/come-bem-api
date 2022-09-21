<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ChangeStoreSessionController extends Controller
{
    public function __invoke($id)
    {
        foreach (session('stores') as $store) {
            if ($store['id'] == $id) {
                session(['store' => $store]);
            }
        }

        return redirect()->back()
            ->withStatus('Loja alterada com sucesso.');
    }
}
