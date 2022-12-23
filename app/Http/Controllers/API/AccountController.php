<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends BaseController
{
    public function show(Request $request, $id)
    {
        $item = Account::query()
            ->findOrFail($id);

        $storeId = $request->get('store')['id'];

        if ($item->store->id != $storeId) {
            return $this->sendError('Conta não cadastrada nessa loja.', [], 403);
        }

        return $this->sendResponse($item);
    }

    public function update(Request $request, $id)
    {
        $item = Account::query()->findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            $this->rules($request, $item->id)
        );

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors(), 422);
        }

        $storeId = $request->get('store')['id'];

        if ($item->store->id != $storeId) {
            return $this->sendError('Conta não cadastrada nessa loja.', [], 403);
        }

        $inputs = $request->all();
        $inputs['daily_limit'] = moneyToFloat($inputs['daily_limit']);

        $item->fill($inputs)->save();

        $user = User::getAllDataUser();

        return $this->sendResponse($user, "Atualização realizada com sucesso", 200);
    }

    public function destroy($id)
    {
        $item = Account::query()->findOrFail($id);

        try {
            $item->delete();
            return $this->sendResponse([], 'Registro deletado com sucesso.');
        } catch (\Throwable $th) {
            return $this->sendError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    public function block(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            ['activate' => ['required', 'boolean']]
        );

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors(), 422);
        }

        $item = Account::query()->findOrFail($id);

        $storeId = $request->get('store')['id'];

        if ($item->store->id != $storeId) {
            return $this->sendError('Conta não cadastrada nessa loja.', [], 401);
        }

        $item->status = $request->get('activate') ? Status::ACTIVE : Status::INACTIVE;
        $item->save();

        $user = User::getAllDataUser();

        return $this->sendResponse($user, 'Atualização realizada com sucesso!', 200);
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'daily_limit' => ['required', 'max:14'],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
