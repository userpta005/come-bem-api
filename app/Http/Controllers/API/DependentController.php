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
            ->with('people', 'accounts.store.people')
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

            $inputs['dependent_id'] = $dependent->id;
            Account::query()->create($inputs);

            $inputs['email'] = $email;
            $inputs['password'] = bcrypt($password);
            $user = User::query()->create($inputs);
            Role::updateOrCreate(
                ['name' => 'dependent', 'guard_name' => 'web'],
                ['description' => 'Dependente']
            );
            $user->assignRole('dependent');

            DB::commit();

            return $this->sendResponse([], "Registro criado com sucesso", 201);
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

    public function update(Request $request, $client, $id)
    {
        $item = Dependent::query()
            ->with('people', 'accounts')
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
            $item->fill($inputs)->save();
            $item->accounts->where('store_id', $inputs['store_id'])->first()->fill($inputs)->save();

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
            'full_name' => ['required', 'max:30'],
            'gender' => ['required', new Enum(PeopleGender::class)],
            'birthdate' => ['required', 'date'],
            'store_id' => ['required', Rule::exists('stores', 'id')],
            'school_year' => ['required', 'max:10'],
            'turn' => ['required', new Enum(\App\Enums\AccountTurn::class)],
            'class' => ['required', 'max:10'],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
