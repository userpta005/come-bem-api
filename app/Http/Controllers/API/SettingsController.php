<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends BaseController
{
    public function __invoke()
    {
        $settings = Settings::with('city.state')->first();

        return $this->sendResponse($settings);
    }
}
