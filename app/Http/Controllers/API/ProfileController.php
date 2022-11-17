<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProfileController extends BaseController
{
    public function show()
    {
        $id = auth()->id();

        $data = User::person()->findOrFail($id);

        return $this->sendResponse($data);
    }

    public function update(Request $request)
    {
        $validator = $this->getValidationFactory()
            ->make(
                $request->all(),
                $this->rules($request, auth()->id())
            );

        if ($validator->fails()) {
            return $this->sendError('Erro de Validação.', $validator->errors()->toArray(), 422);
        }

        $user = User::find($request->user()->id);

        DB::transaction(function () use ($request, $user) {
            $person_id = $request->user()->person_id;

            $inputs = $request->all();

            $person = Person::find($person_id);

            $person->fill($inputs)->save();

            $user->fill($inputs)->save();
        });

        return $this->sendResponse([], "Perfil atualizado com sucesso.");
    }


    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'max:120'],
            'email' => ['required', 'max:89', Rule::unique('users')->ignore($request->user()->id)],
            'phone' => ['required', 'max:15']
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
