<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Active;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends BaseController
{

    public function index()
    {
        $faqs = Faq::where('is_active', Active::YES)
            ->get();

        return $this->sendResponse($faqs);
    }
}
