<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Cashier;
use Illuminate\Http\Request;

class CashierController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:cashiers_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:cashiers_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:cashiers_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:cashiers_delete', ['only' => ['destroy']]);
        $this->middleware('store');
    }

    public function index(Request $request)
    {
        $query = Cashier::query()
            ->where('status', '!=', 3)
            ->where('store_id', $request->get('store')['id']);

        $data = $request->filled('page') ? $query->paginate(10) : $query->get();

        return $this->sendResponse($data);
    }

    public function show(Request $request, $id)
    {
        $item = Cashier::query()
            ->where('status', '!=', 3)
            ->where('store_id', $request->get('store')['id'])
            ->findOrFail($id);

        return $this->sendResponse($item);
    }
}
