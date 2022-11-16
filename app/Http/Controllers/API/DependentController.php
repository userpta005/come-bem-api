<?php

namespace App\Http\Controllers\API;

use App\Models\Dependent;
use Illuminate\Http\Request;

class DependentController extends BaseController
{
    public function destroy($id)
    {
        $item = Dependent::findOrFail($id);

        try {
            $item->delete();
            return $this->sendResponse([], 'Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return $this->sendError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }
}
