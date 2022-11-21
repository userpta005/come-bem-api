<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends BaseController
{
    public function index(Request $request)
    {
        $sections = Section::query()
            ->where('status', Status::ACTIVE)
            ->where('store_id', $request->get('store')['id'])
            ->get()
            ->toTree();

        return $this->sendResponse($sections);
    }

    public function show(Request $request, $id)
    {
        $item = Section::query()
        ->where('store_id', $request->get('store')['id'])
        ->findOrFail($id);

        return $this->sendResponse($item);
    }
}
