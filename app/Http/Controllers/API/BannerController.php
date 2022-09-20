<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Active;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends BaseController
{
    public function index(Request $request)
    {

        $query =  Banner::query()
            ->where('banners.is_active', Active::YES)
            ->when($request->has('position'), function ($query) use ($request) {
                $query->where('banners.position', $request->position);
            })
            ->when($request->has('type'), function ($query) use ($request) {
                $query->where('banners.type', $request->type);
            })
            ->orderBy("sequence");

        ($request->has('page'))  ? $data = $query->paginate(10) : $data = $query->get();

        return $this->sendResponse($data);
    }
}
