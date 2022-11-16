<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends BaseController
{
    public function destroy($id)
    {
        $item = Client::findOrFail($id);

        try {
            $item->delete();
            return $this->sendResponse([], 'Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return $this->sendError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }
}
