<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends BaseController
{

    public function index(Request $request)
    {
        $query = Faq::query()
            ->where('status', Status::ACTIVE);

        $data = $request->filled('page') ? $query->paginate(10) : $query->get();

        return $this->sendResponse($data);
    }

    public function show(Request $request, $id)
    {
        $item = Faq::query()
            ->where('status', Status::ACTIVE)
            ->findOrFail($id);

        return $this->sendResponse($item);
    }
}
