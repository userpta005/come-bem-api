<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CardController extends BaseController
{
    public function destroy($id)
    {
        $item = Card::query()->findOrFail($id);

        try {
            $item->delete();
            return $this->sendResponse([], 'Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return $this->sendError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    public function block(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'cards' => ['required', 'array', 'min:1'],
                'cards.*.id' => ['required', 'integer', Rule::exists('cards', 'id')],
                'cards.*.status' => ['required', 'integer', new Enum(Status::class)],
                'cards.*.account' => ['required', 'array'],
                'cards.*.account.store_id' => ['required', 'integer', Rule::exists('stores', 'id')],
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors(), 422);
        }

        $storeId = $request->get('store')['id'];
        $cards = $request->get('cards');

        foreach ($cards as $card) {
            $item = Card::query()->findOrFail($card['id']);
            if ($card['account']['store_id'] != $storeId) {
                return $this->sendError('Conta não cadastrada nessa loja.', [], 401);
            }
            $item->status = $card['status'];
            $item->save();
        }

        $user = User::getAllDataUser();

        return $this->sendResponse($user, 'Atualização realizada com sucesso!', 200);
    }
}
