<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Kalnoy\Nestedset\Collection;
use Illuminate\Support\Facades\Validator;

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
        $data =  Section::withDepth()->withCount('descendants')->get()->toFlatTree();

        return view('sections.index', compact('data'));
    }

    public function create(Request $request)
    {
        $data = $request->parent_id;

        $sections = $this->getPlanOptions();

        $types = Section::types();

        $section = Section::withDepth()->find($data);

        if (!$section) {
            $types = array_splice($types, 0, 1);
        } else if ($section->depth == 1) {
            $types = array_splice($types, 1, 1);
        }

        return view('sections.create', compact(
            'data',
            'sections',
            'types'
        ));
    }

    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();

        $inputs = $request->all();
        $inputs['use'] = 1;
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

        $sections = $this->getPlanOptions();

        $data = $item->parent_id;

        $types = Section::types();

        $section = Section::withDepth()->find($data);

        if (!$section) {
            $types = array_splice($types, 0, 1);
        } else if ($section->depth == 1) {
            $types = array_splice($types, 1, 1);
        }

        return view('sections.edit', compact(
            'item',
            'sections',
            'data',
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


        $item->fill($request->all())->save();

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
            'name' => ['required', 'max:35'],
            'descriptive' => ['nullable', 'max:120'],
            'type' => ['required'],
            'is_enabled' => ['required'],
            'order' => ['required']
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }

    /**
     * @param Collection $items
     *
     * @return static
     */
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