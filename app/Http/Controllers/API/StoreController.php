<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends BaseController
{
    public function index(Request $request)
    {
        $query = Store::query()
            ->with('people.city.state', 'tenant.people.city.state');

        $data = $request->filled('page') ? $query->paginate(10) : $query->get();

        return $this->sendResponse($data);
    }

    public function show($id)
    {
        $item = Store::query()
            ->with('people.city.state', 'tenant.people.city.state')
            ->findOrFail($id);

        return $this->sendResponse($item);
    }
}
