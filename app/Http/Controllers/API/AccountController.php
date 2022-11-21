<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\Account;
use App\Models\Dependent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AccountController extends BaseController
{
    public function show(Request $request, Dependent $dependent, $id)
    {
        $item = Account::query()
            ->where('status', Status::ACTIVE)
            ->where('store_id', $request->get('store')['id'])
            ->where('dependent_id', $dependent->id)
            ->findOrFail($id);

        return $this->sendResponse($item);
    }

    public function update(Request $request, Dependent $dependent, $id)
    {
        $item = Account::query()
            ->where('store_id', $request->get('store')['id'])
            ->where('dependent_id', $dependent->id)
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

            $item->fill($inputs)->save();

            DB::commit();
            return $this->sendResponse([], "Limite diário atualizado com sucesso", 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = Account::query()->findOrFail($id);

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
            'daily_limit' => ['required', 'max:14'],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
