<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Parameter;
use Illuminate\Http\Request;

class ParameterController extends BaseController
{
    public function index()
    {
        $parameters = Parameter::orderBy('name')->get();

        return $this->sendResponse($parameters);
    }
}
