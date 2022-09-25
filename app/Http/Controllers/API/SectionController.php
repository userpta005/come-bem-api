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
            ->get(['id', 'name', 'description', 'parent_id', '_lft', '_rgt', 'order'])
            ->toTree();

        return $this->sendResponse($sections);
    }
}