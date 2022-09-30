<?php

namespace App\Http\Controllers\API;

use App\FinancialCategory;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;

class FinancialCategoryController extends BaseController
{
    public function index(Request $request)
    {

        $data = FinancialCategory::withDepth()->where('is_enabled', true)
            ->when($request->has('tenant'), function ($query) use ($request) {
                $query->where('tenant_id', $request->tenant);
            })
            ->when($request->has('type'), function ($query) use ($request) {
                if ($request->type == 3) {
                    return $query->where('code', 'LIKE', '3%')->where('code', '!=', '3.01.002');
                }
                if ($request->type == 4) {
                    return $query->where('code', 'LIKE', '3%')->where('code', '!=', '3.01.001');
                }
                return $query->where('type', $request->type);
            })
            ->get()
            ->toFlatTree();

        return $this->sendResponse($data);
    }
}
