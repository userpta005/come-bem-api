<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagseguroAuthorizationController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $callback = route('pagseguro.authorization');

        $url = 'https://connect.pagseguro.uol.com.br/oauth2/authorize?response_type=code&client_id=' . config('laravel-pagseguro.client_id');
        $url .= "&redirect_uri={$callback}&scope=payments.read+payments.create&state={$request->tenant}";

        return $this->sendResponse(['url' => $url]);
    }
}
