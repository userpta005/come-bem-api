<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends BaseController
{
    public function search(Request $request)
    {
        $query = Client::query();

        if ($request->has('search')) {
            $query->person()
                ->where(function ($query) use ($request) {
                    $query->where('people.name', 'like', '%' . $request->search . '%')
                        ->orWhere('people.full_name', 'like', '%' . $request->search . '%')
                        ->orWhereRaw('(replace(replace(replace(people.nif, ".", ""), "/", ""), "-", "") like "%' . removeMask($request->search) . '%")');
                });
        }

        $data = $query->orderBy('people.name')->get();

        return $this->sendResponse($data);
    }
}
