<?php

namespace App\Http\Controllers\API;

use App\Enums\LeadStatus;
use App\Http\Controllers\API\BaseController;
use App\Models\Lead;
use App\Models\Person;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LeadController extends BaseController
{
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules($request)
        );

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors(), 422);
        }

        try {
            DB::beginTransaction();

            $inputs = $request->all();
            $inputs['status'] = LeadStatus::ACTIVE;

            $person = Person::query()
                ->updateOrCreate(
                    ['email' => $inputs['email']],
                    $inputs
                );

            $inputs['person'] = $person->id;
            Lead::query()
                ->updateOrCreate(
                    ['person_id' => $person->id, 'store_id' => $request->get('store')['id']],
                    $inputs
                );

            DB::commit();
            return $this->sendResponse([], "Lead criado com sucesso", 201);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError("", $e->getMessage(), 500);
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'max:100'],
            'email' => ['required', 'max:100', Rule::unique('people')->ignore($primaryKey)],
            'phone' => ['required', 'max:15'],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
