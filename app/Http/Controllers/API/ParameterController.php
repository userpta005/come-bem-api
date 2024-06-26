<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\Parameter;
use Illuminate\Http\Request;

class ParameterController extends BaseController
{
    public function index(Request $request)
    {
        $query = Parameter::query()
            ->orderBy('name');

        $data = $request->filled('page') ? $query->paginate(10) : $query->get();

        return $this->sendResponse($data);
    }

    public function show(Request $request, $id)
    {
        $item = Parameter::query()->findOrFail($id);

        return $this->sendResponse($item);
    }
}
