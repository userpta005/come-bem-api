<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends BaseController
{
    public function index(Request $request)
    {

        $query =  Banner::query()
            ->where('banners.status', Status::ACTIVE)
            ->when($request->filled('position'), function ($query) use ($request) {
                $query->where('banners.position', $request->position);
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('banners.type', $request->type);
            })
            ->orderBy("sequence");

        ($request->filled('page'))  ? $data = $query->paginate(10) : $data = $query->get();

        return $this->sendResponse($data);
    }
}
