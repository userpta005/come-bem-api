<?php

namespace App\Http\Controllers\API;

use App\Models\MeasurementUnit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class MeasurementUnitController extends BaseController
{
    public function store(Request $request)
    {
        $this->validate(
            $request,
            $this->rules($request)
        );

        $item = MeasurementUnit::create($request->all());

        return response($item->toJson(), 200);
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'max:20'],
            'initials' => ['required', 'max:6', Rule::unique('measurement_units')->ignore($primaryKey)],
            'status' => ['required', new Enum(\App\Enums\Common\Status::class)],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
