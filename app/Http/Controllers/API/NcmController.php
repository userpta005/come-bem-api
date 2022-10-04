<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Ncm;
use Illuminate\Http\Request;

class NcmController extends BaseController
{
    public function search(Request $request)
    {
        $query = Ncm::orderBy('description', 'asc');

        if ($request->filled('search')) {
            $query = $query->where('description', 'like', '%' . $request->search . '%');
        }

        $data = $query->select('id', 'description', 'code')->get();
        
        return $this->sendResponse($data);
    }
}
