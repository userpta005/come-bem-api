<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Common\Status;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Store;
use Illuminate\Http\Request;

class ClientDependentReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:client-dependents-report_view', ['only' => ['clientDependents']]);
    }

    public function __invoke(Request $request)
    {
        $store = Store::query()->person()->findOrFail($request->store_id);

        $clients = Client::query()
            ->person()
            ->with(['dependents.accounts'])
            ->whereHas('dependents', function ($query) use ($request) {
                $query->where('status', Status::ACTIVE)
                    ->whereHas('accounts', function ($query) use ($request) {
                        $query->where('store_id', $request->store_id);
                    });
            })
            ->get();

        return view('reports.client-dependents.report', compact('clients', 'store'));
    }
}
