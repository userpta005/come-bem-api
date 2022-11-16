<?php

namespace App\Http\Controllers\API;

use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends BaseController
{
    public function index(Request $request)
    {
        $query = Person::query();

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('full_name', 'like', '%' . $request->search . '%')
                    ->orWhereRaw('(replace(replace(replace(nif, ".", ""), "/", ""), "-", "") like "%' . removeMask($request->search) . '%")');
            });
        }

        $data = $query->get();

        return $this->sendResponse($data);
    }
}
