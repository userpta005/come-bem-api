<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends BaseController
{
    public function index(Request $request)
    {
        $query = Banner::query()
            ->where('status', Status::ACTIVE)
            ->when($request->filled('position'), function ($query) use ($request) {
                $query->where('position', $request->position);
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->orderBy("sequence");

        $data = $request->filled('page') ? $query->paginate(10) : $query->get();

        return $this->sendResponse($data);
    }

    public function show(Request $request, $id)
    {
        $item = Banner::query()
            ->where('status', Status::ACTIVE)
            ->findOrFail($id);

        return $this->sendResponse($item);
    }
}
