<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:faqs_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:faqs_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:faqs_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:faqs_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = Faq::orderBy('question')->paginate(10);

        return view('faqs.index', compact('data'));
    }

    public function create()
    {
        return view('faqs.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules($request));

        Faq::create($request->all());

        return redirect()->route('faqs.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show($id)
    {
        $item = Faq::findOrFail($id);

        return view('faqs.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Faq::findOrFail($id);

        return view('faqs.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Faq::findOrFail($id);

        $this->validate(
            $request,
            $this->rules($request, $item->id)
        );

        $inputs = $request->all();

        $item->fill($inputs)->save();

        return redirect()->route('faqs.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = Faq::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('faqs.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('faqs.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'question' => ['required', 'max:40'],
            'answer' => ['required'],
            'url' => ['nullable', 'url'],
            'status' => ['required'],
            'position' => ['required']
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
