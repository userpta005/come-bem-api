<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends BaseController
{
    public function index(Request $request)
    {
        $query = PaymentMethod::query()
            ->where('status', Status::ACTIVE);

        $data = $request->filled('page') ? $query->paginate(10) : $query->get();

        return $this->sendResponse($data);
    }

    public function show($id)
    {
        $item = PaymentMethod::query()->findOrFail($id);

        return $this->sendResponse($item);
    }
}
