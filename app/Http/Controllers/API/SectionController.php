<?php

namespace App\Http\Controllers\API;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends BaseController
{
    public function index(Request $request)
    {
        $sections = Section::query()
            ->where('is_enabled', true)
            ->get(['id', 'name', 'parent_id', '_lft', '_rgt', 'order'])
            ->toTree();

        return $this->sendResponse($sections);
    }
}