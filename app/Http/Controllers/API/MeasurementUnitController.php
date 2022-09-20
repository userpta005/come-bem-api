<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MeasurementUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeasurementUnitController extends BaseController
{
    public function store(Request $request)
    {
        $this->validate(
            $request,
            $this->rules()
        );

        $item = MeasurementUnit::create($request->all());

        return response($item->toJson(), 200);
    }

    private function rules()
    {
        $rules = [
            'initials' => ['required', 'max:4', 'unique:measurement_units'],
            'name' => ['required', 'max:20'],
            'is_enabled' => ['required'],
        ];

        return $rules;
    }
}
