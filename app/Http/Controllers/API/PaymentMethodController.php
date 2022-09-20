<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends BaseController
{
    public function index()
    {
        $data = PaymentMethod::where('is_enabled', true)->get();

        return $this->sendResponse($data);
    }
}
