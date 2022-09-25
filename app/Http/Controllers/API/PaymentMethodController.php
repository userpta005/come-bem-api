<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\PaymentMethod;

class PaymentMethodController extends BaseController
{
    public function index()
    {
        $data = PaymentMethod::where('status', Status::ACTIVE)->get();

        return $this->sendResponse($data);
    }
}
