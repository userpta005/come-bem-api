<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Common\Status;
use App\Enums\SectionType;
use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kalnoy\Nestedset\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sections_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:sections_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:sections_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:sections_delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data = Section::withDepth()
            ->withCount('descendants')
            ->when(session()->has('store'), fn ($query) => $query->where('store_id', session('store')['id']))
            ->when(!session()->has('store'), fn ($query) => $query->whereNull('store_id'))
            ->get()
            ->toFlatTree();

        return view('sections.index', compact('data'));
    }

    public function create(Request $request)
    {
        $parentId = $request->parent_id;

        $sections = $this->getPlanOptions();

        $section = Section::withDepth()->find($parentId);

        $types = SectionType::all();
        if (!empty($section) && $section->depth == 1) {
            $types = SectionType::only(['A']);
        }

        return view('sections.create', compact(
            'parentId',
            'sections',
            'types'
        ));
    }

    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            $this->rules($request, $request->parent_id ?? null)
        )->validate();

        $inputs = $request->all();
        $inputs['store_id'] = session()->has('store') ? session('store')['id'] : null;
        $inputs['use'] = 1;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $upload = $request->file('image')->store('sections', 'public');
            $inputs['image'] = $upload;
        }

        Section::create($inputs);

        return redirect()->route('sections.index')
            ->withStatus('Registro criado com sucesso.');
    }

    public function show($id)
    {
        $item = Section::findOrFail($id);

        return view('sections.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Section::findOrFail($id);

        $parentId = $item->parent_id;

        $sections = $this->getPlanOptions();

        $section = Section::withDepth()->find($parentId);

        $types = SectionType::all();
        if (!empty($section) && $section->depth == 1) {
            $types = SectionType::only(['A']);
        }

        return view('sections.edit', compact(
            'item',
            'sections',
            'parentId',
            'types'
        ));
    }

    public function update(Request $request, $id)
    {
        $item = Section::findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $item->getKey())
        )->validate();

        $inputs = $request->all();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $upload = $request->file('image')->store('sections', 'public');
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $inputs['image'] = $upload;
        }
        $item->fill($inputs)->save();

        return redirect()->route('sections.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = Section::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('sections.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('sections.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'name' => ['required', 'string', 'max:40'],
            'type' => ['required', new Enum(SectionType::class)],
            'status' => ['required', new Enum(Status::class)],
            'description' => ['nullable', 'max:120'],
            'order' => ['required'],
            'parent_id' => ['nullable']
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }

    protected function makeOptions(Collection $items)
    {
        $options = ['' => 'Não Contém'];
        foreach ($items as $item) {
            $options[$item->getKey()] = $item->name;
        }
        return $options;
    }

    protected function getPlanOptions($except = null)
    {
        /** @var \Kalnoy\Nestedset\QueryBuilder $query */

        $query = Section::select('id', 'name')->withDepth();

        if ($except) {
            $query->whereNotDescendantOf($except)->where('id', '<>', $except->id);
        }
        return $this->makeOptions($query->get());
    }
}
