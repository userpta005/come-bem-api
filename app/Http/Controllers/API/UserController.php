<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules($request)
        );

        if ($validator->fails()) {
            return $this->sendError('Erro de Validação.', $validator->errors()->toArray(), 422);
        }

        $inputs = $request->all();
        $inputs['password'] = bcrypt($request->input('password'));
        $inputs['status'] = Status::ACTIVE;

        $person = Person::query()
            ->updateOrCreate(
                ['email' => $inputs['email']],
                $inputs
            );

        $user = User::query()
            ->updateOrCreate(
                ['person_id' => $person->id],
                $inputs
            );

        return $this->sendResponse([], 'Registro criado com sucesso', 201);
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'max:120'],
            'email' => ['required', 'max:89', Rule::unique('users')->ignore($primaryKey)],
            'phone' => ['required', 'max:15'],
            'password' => ['required', 'min:8']
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
