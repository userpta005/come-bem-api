<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Enums\PeopleGender;
use App\Models\Account;
use App\Models\Client;
use App\Models\Dependent;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Throwable;

class DependentController extends BaseController
{
    public function index(Request $request)
    {
        $query = Dependent::query()
            ->with(['people.city.state', 'accounts']);

        $data = $request->filled('page') ? $query->paginate(10) : $query->get();

        return $this->sendResponse($data);
    }

    public function store(Request $request, Client $client)
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

            $inputs['person'] = $person->id;
            $inputs['client_id'] = $client->id;
            $dependent = Dependent::query()->create($inputs);

            $inputs['dependent_id'] = $dependent->id;
            Account::query()->create($inputs);

            DB::commit();
            return $this->sendResponse([], "Registro criado com sucesso", 201);
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    public function show(Request $request, Client $client, $id)
    {
        $item = Dependent::query()
            ->with('people.city.state', 'accounts')
            ->where('status', Status::ACTIVE)
            ->findOrFail($id);

        return $this->sendResponse($item);
    }

    public function update(Request $request, Client $client, $id)
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

    public function block(Request $request, Client $client, $id)
    {
        $item = Dependent::query()->findOrFail($id);
        $item->status = Status::INACTIVE;
        $item->save();
        $account = $item->accounts->where('store_id', $request->get('store')['id'])->first();
        $account->status = Status::INACTIVE;
        $account->save();
        return $this->sendResponse([], 'Dependente e Conta inativado com sucesso.');
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'max:100'],
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
