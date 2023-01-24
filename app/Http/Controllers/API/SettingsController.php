<?php

namespace App\Http\Controllers\API;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends BaseController
{
    public function index(Request $request)
    {
        $settings = Settings::query()
            ->with('city.state')
            ->get();

        return $this->sendResponse($settings);
    }
}
