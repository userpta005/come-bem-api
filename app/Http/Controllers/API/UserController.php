<?php

namespace App\Http\Controllers\API;

use App\Enums\ClientType;
use App\Enums\Common\Status;
use App\Models\Account;
use App\Models\Client;
use App\Models\Dependent;
use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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

        try {
            DB::beginTransaction();

            $inputs = $request->all();
            $inputs['password'] = bcrypt(preg_replace("/\D+/", "", $inputs['phone']));
            $inputs['status'] = Status::INACTIVE;

            $person = Person::query()
                ->updateOrCreate(
                    ['email' => $inputs['email']],
                    $inputs
                );

            $inputs['person_id'] = $person->id;

            $user = User::query()
                ->updateOrCreate(
                    ['person_id' => $person->id],
                    $inputs
                );

            Role::updateOrCreate(
                ['name' => 'client'],
                [
                    'description' => 'Cliente'
                ]
            );

            $user->assignRole('client');

            $inputs['status'] = Status::ACTIVE;
            $client = Client::query()
                ->updateOrCreate(
                    ['person_id' => $person->id],
                    $inputs
                );

            if ($inputs['type'] === ClientType::RESPONSIBLE_DEPENDENT->value) {
                $inputs['client_id'] = $client->id;
                $dependent = Dependent::query()
                    ->updateOrCreate(
                        ['client_id' => $client->id],
                        $inputs
                    );

                Account::query()
                    ->updateOrCreate(
                        ['dependent_id' => $dependent->id, 'store_id' => $inputs['store_id']],
                        $inputs
                    );
            }

            DB::commit();
            return $this->sendResponse([], 'Registro criado com sucesso', 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    public function show()
    {
        $user = User::getAllDataUser();

        return $this->sendResponse($user);
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'max:100'],
            'email' => ['required', 'max:100', Rule::unique('people')->ignore($primaryKey)],
            'phone' => ['required', 'max:15'],
            'birthdate' => ['required', 'date'],
            'city_id' => ['required', Rule::exists('cities', 'id')],
            'type' => ['required', new Enum(ClientType::class)],
            'store_id' => ['required', Rule::exists('stores', 'id')],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
