<?php

namespace App\Http\Controllers\API;

use App\Enums\Common\Status;
use App\Models\Faq;

class FaqController extends BaseController
{

    public function index()
    {
        $faqs = Faq::where('status', Status::ACTIVE)
            ->get();

        return $this->sendResponse($faqs);
    }
}
