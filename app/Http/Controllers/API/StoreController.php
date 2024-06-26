<?php

namespace App\Http\Controllers\API;

use App\Enums\StoreStatus;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends BaseController
{
    public function index(Request $request)
    {
        $query = Store::person()
            ->where('stores.status', StoreStatus::ENABLED)
            ->when($request->filled('city_id'), function ($query) use ($request) {
                $query->whereHas('people.city', fn ($query) => $query->where('id', $request->city_id));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('people.name', 'like', '%' . $request->search . '%')
                        ->orWhere('people.full_name', 'like', '%' . $request->search . '%');
                });
            });

        $data = $request->filled('page') ? $query->paginate(10) : $query->get();

        return $this->sendResponse($data);
    }

    public function show($id)
    {
        $item = Store::query()
            ->with('people.city.state', 'tenant.people.city.state')
            ->where('status', StoreStatus::ENABLED)
            ->findOrFail($id);

        return $this->sendResponse($item);
    }
}
