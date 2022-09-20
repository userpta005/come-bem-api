<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Ncm;
use Illuminate\Http\Request;

class NcmController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ncms_view', ['only' => ['show', 'index']]);
    }

    public function index(Request $request)
    {
        $data = Ncm::orderBy('description', 'asc')->paginate(10);

        return view('ncms.index', compact('data'));
    }
}
