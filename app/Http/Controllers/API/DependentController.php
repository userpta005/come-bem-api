<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Enums\PeopleGender;
use App\Models\Account;
use App\Models\Dependent;
use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Throwable;

class DependentController extends BaseController
{
    public function index(Request $request, $client)
    {
        $query = Dependent::query()
            ->with('people.city', 'accounts.store.people', 'accounts.orders')
            ->where('client_id', $client);

        $data = $request->filled('page') ? $query->paginate(10) : $query->get();

        return $this->sendResponse($data);
    }

    public function store(Request $request, $client)
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

            $dependentExists = Dependent::query()
                ->whereHas('people', fn ($query) => $query->where('full_name', 'like', '%' . $inputs['full_name'] . '%'))
                ->where('client_id', $client)
                ->first();

            if ($dependentExists) {
                return $this->sendError('Nome social já utilizado !', [], 403);
            }

            $inputs['status'] = Status::ACTIVE;

            $person = Person::query()->create($inputs);

            $consoantes = "";
            $contador = 0;
            for ($i = 0; $i < strlen($inputs['name']); $i++) {
                if (preg_match("/[bcdfghjklmnpqrstvwxyz]/i", $inputs['name'][$i])) {
                    $consoantes .= $inputs['name'][$i];
                    $contador++;
                }
                if ($contador == 3) {
                    break;
                }
            }

            $email = strtolower($consoantes);
            $password = implode('', [mt_rand(0, 9), mt_rand(0, 9), mt_rand(0, 9), mt_rand(0, 9)]);
            $inputs['access_key'] = $email . $password;
            $inputs['person_id'] = $person->id;
            $inputs['client_id'] = $client;
            $dependent = Dependent::query()->create($inputs);

            foreach ($inputs['accounts'] as $values) {
                $values['dependent_id'] = $dependent->id;
                $values['daily_limit'] = moneyToFloat($inputs['daily_limit']);
                $values['status'] = $inputs['status'];
                Account::query()->create($values);
            }

            $inputs['email'] = $email;
            $inputs['password'] = bcrypt($password);
            $user = User::query()->create($inputs);
            Role::updateOrCreate(
                ['name' => 'dependent', 'guard_name' => 'web'],
                ['description' => 'Consumidor']
            );
            $user->assignRole('dependent');

            DB::commit();

            $dependent->load(['people', 'accounts.store.people']);

            return $this->sendResponse($dependent, "Registro criado com sucesso", 201);
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    public function show($id)
    {
        $item = Dependent::query()
            ->with([
                'people',
                'accounts' => [
                    'store.people',
                    'orders.orderItems' => function ($query) {
                        $query->with(['product' => ['um', 'stock']])
                            ->whereDate('date', '>=', today());
                    }
                ]
            ])
            ->findOrFail($id);

        return $this->sendResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = Dependent::query()
            ->with('people.user', 'accounts')
            ->findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            $this->rules($request, $item->id)
        );

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors(), 422);
        }

        try {
            DB::beginTransaction();

            $inputs = $request->all();

            $item->people->fill($inputs)->save();

            if (!empty($request['access_key'])) {
                $inputs['access_key'] = $request['access_key']['email'] . $request['access_key']['password'];
                $inputs['email'] = $request['access_key']['email'];
                $inputs['password'] = bcrypt($request['access_key']['password']);
            }

            $item->fill($inputs)->save();
            $item->people->user->fill($inputs)->save();

            foreach ($inputs['accounts'] as $values) {
                $values['dependent_id'] = $item->id;
                $values['daily_limit'] = moneyToFloat($inputs['daily_limit']);
                $values['status'] = Status::ACTIVE;
                if (!empty($values['id'])) {
                    Account::query()->findOrFail($values['id'])->fill($values)->save();
                } else {
                    Account::query()->create($values);
                }
            }

            DB::commit();
            return $this->sendResponse([], "Registro atualizado com sucesso", 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = Dependent::query()->findOrFail($id);

        try {
            $item->delete();
            return $this->sendResponse([], 'Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return $this->sendError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'max:100'],
            'birthdate' => ['required', 'date'],
            'full_name' => ['required', 'max:30'],
            'gender' => ['required', new Enum(PeopleGender::class)],
            'city_id' => ['required', Rule::exists('cities', 'id')],
            'daily_limit' => ['nullable', 'max:14'],
            'accounts' => ['required', 'array', 'min:1'],
            'accounts.*.store_id' => ['required', Rule::exists('stores', 'id')],
            'accounts.*.school_year' => ['required', 'max:10'],
            'accounts.*.turn' => ['required', new Enum(\App\Enums\AccountTurn::class)],
            'accounts.*.class' => ['required', 'max:10'],
            'access_key' => ['nullable', 'array'],
            'access_key.email' => ['nullable', 'min:3'],
            'access_key.password' => ['nullable', 'confirmed', 'min:4']
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
