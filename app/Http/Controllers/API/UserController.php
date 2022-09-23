<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function search(Request $request)
    {
        $query = User::query();

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
