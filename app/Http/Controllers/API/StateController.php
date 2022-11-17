<?php

namespace App\Http\Controllers\API;

use App\Models\State;
use Illuminate\Http\Request;

class StateController extends BaseController
{
    public function index(Request $request)
    {
        $query = State::query()->orderBy('title');

        $data = $request->filled('page') ? $query->paginate(10) : $query->get();

        return $this->sendResponse($data);
    }

    public function show($id)
    {
        $item = State::query()->findOrFail($id);

        return $this->sendResponse($item);
    }
}
