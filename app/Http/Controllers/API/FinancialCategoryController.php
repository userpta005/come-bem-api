<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\FinancialCategory;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;

class FinancialCategoryController extends BaseController
{
    public function index(Request $request)
    {
        $data = FinancialCategory::withDepth()
            ->where('status', Status::ACTIVE)
            ->when($request->filled('tenant'), function ($query) use ($request) {
                $query->where('tenant_id', $request->tenant);
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                if ($request->type == 3) {
                    $query->where('code', 'LIKE', '3%')->where('code', '!=', '3.01.002');
                }
                if ($request->type == 4) {
                    $query->where('code', 'LIKE', '3%')->where('code', '!=', '3.01.001');
                }
                $query->where('type', $request->type);
            })
            ->get()
            ->toFlatTree();

        return $this->sendResponse($data);
    }
}
