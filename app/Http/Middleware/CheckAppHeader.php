<?php

namespace App\Http\Middleware;

use App\Models\Store;
use Closure;
use Illuminate\Http\Request;

class CheckAppHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (session()->exists('store')) {
            $store = Store::person()
            ->with('tenant')
            ->findOrFail(session('store')['id']);
            $request->attributes->add(['store' => $store->toArray()]);
            return $next($request);
        }

        if (!$request->headers->has('app')) {
            return response()->json([
                'message' => 'App ID não informada.'
            ], 403);
        }

        $store = Store::person()
            ->with('tenant')
            ->where('app_token', $request->header('app'))
            ->first();

        if (!$store) {
            return response()->json([
                'message' => 'App ID não válida.'
            ], 403);
        }

        $request->attributes->add(['store' => $store->toArray()]);

        return $next($request);
    }
}
