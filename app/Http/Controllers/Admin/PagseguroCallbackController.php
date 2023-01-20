<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PagseguroCallbackController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if (!$request->code) {
            return view('auth.error');
        }

        $tenant = Tenant::findOrFail($request->state);

        $isSandbox = boolval(config('laravel-pagseguro.use-sandbox'));

        $url = config('laravel-pagseguro.host.production');

        if ($isSandbox) {
            $url = config('laravel-pagseguro.host.sandbox');
        }


        $callback = route('pagseguro.authorization');

        $data = [
            "grant_type" => "authorization_code",
            "redirect_uri" => $callback,
            "code" => $request->code
        ];


        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X_CLIENT_ID' => config('laravel-pagseguro.client_id'),
            'X_CLIENT_SECRET' => config('laravel-pagseguro.client_secret'),
        ])
            ->withToken(config('laravel-pagseguro.token'))
            ->post(
                "{$url}/oauth2/token",
                $data
            );

        if ($response->failed()) {
            Log::debug(json_encode($response->body()));
            return view('auth.error');
        }

        $pagseguro = [];

        $pagseguro['token'] = $response['access_token'];
        $pagseguro['refresh_token'] = $response['refresh_token'];
        $pagseguro['expires_in'] = Carbon::now()->addSeconds($response['expires_in'])->format('Y-m-d');
        $pagseguro['account_id'] = $response['account_id'];

        $tenant->pagseguro = $pagseguro;
        $tenant->save();

        return view('auth.success');
    }
}
