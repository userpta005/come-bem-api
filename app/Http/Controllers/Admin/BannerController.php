<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:banners_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:banners_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:banners_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:banners_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = Banner::orderBy('created_at', 'desc')->paginate(10);

        return view('banners.index', compact('data'));
    }

    public function create()
    {
        return view('banners.create');
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            $this->rules($request)
        );

        $inputs = $request->all();

        $item = new Banner($inputs);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $upload = $request->image->store('banners', 'public');

            $item->image = $upload;
        }

        $item->save();

        return redirect()->route('banners.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show($id)
    {
        $item = Banner::findOrFail($id);

        return view('banners.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Banner::findOrFail($id);

        return view('banners.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            $this->rules($request, $id)
        );

        $item = Banner::findOrFail($id);

        $inputs = $request->all();

        $item->fill($inputs);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $upload = $request->image->store('banners', 'public');

            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $item->image = $upload;
        }

        $item->save();

        return redirect()->route('banners.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = Banner::findOrFail($id);

        try {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $item->delete();
            return redirect()->route('banners.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('banners.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'max:100'],
            'status' => ['required'],
            'position' => ['required'],
            'type' => ['required'],
            'sequence' => ['required'],
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg'],
        ];

        if (empty($primaryKey)) {
            $rules['image'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'];
        }

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
