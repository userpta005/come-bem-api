<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends BaseController
{
    public function index(Request $request)
    {
        $query = City::query()
            ->with('state')
            ->when($request->filled('state_id'), function ($query) use ($request) {
                $query->where('state_id', $request->state_id);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->orderBy('title', 'asc');

        $data = $request->filled('page') ? $query->paginate(10) : $query->get();

        return $this->sendResponse($data);
    }

    public function show($id)
    {
        $item = City::query()
        ->with('state')
        ->findOrFail($id);

        return $this->sendResponse($item);
    }
}
