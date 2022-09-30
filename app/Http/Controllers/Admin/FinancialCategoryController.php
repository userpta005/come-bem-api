<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Common\Status;
use App\Enums\FinancialCategoryClass;
use App\Enums\FinancialCategoryType;
use App\Models\FinancialCategory;
use App\Http\Controllers\Controller;
use App\Models\Parameter;
use App\Models\PlanOfAccounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Kalnoy\Nestedset\Collection;

class FinancialCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:financialcategories_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:financialcategories_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:financialcategories_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:financialcategories_delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $mask = Parameter::where('name', 'CATEGF')->first();

        $data = FinancialCategory::withDepth()
            ->get()
            ->toTree();

        return view('financialcategories.index', compact('data', 'mask'));
    }

    public function create(Request $request)
    {
        $data = $request->only('parent_id');

        $categories = $this->getPlanOptions();

        return view('financialcategories.create', compact(
            'data',
            'categories',
        ));
    }

    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();

        DB::transaction(function () use ($request) {
            $mask = Parameter::where('name', 'CATEGF')->first();

            $costCenter = new FinancialCategory($request->all());

            if ($request->parent_id) {
                $center = FinancialCategory::withDepth()->find($request->parent_id);

                $child = FinancialCategory::defaultOrder()
                    ->where('parent_id', $request->parent_id)
                    ->get()
                    ->last();

                $value = $center->depth + 1;
                $cod = 1;
                $class = strlen($mask->value) !== $center->depth + 2 ? FinancialCategoryClass::SYNTHETIC : FinancialCategoryClass::ANALYTICAL;
                if ($child) {
                    $codesArray = explode(".", $child->code);
                    $cod = end($codesArray);
                    $cod = (int) $cod + 1;
                }
                $mask = "%'.0" . $mask->value[$value] . "d";
                $code = $center->code . '.' . sprintf($mask, $cod);
            } else {
                $last = FinancialCategory::whereIsRoot()
                    ->orderBy('code', 'desc')
                    ->first();

                $mask = "%'.0" . $mask->value[0] . "d";
                $cod = ($last ? (int) $last->code + 1 : 1);
                $code = sprintf($mask, $cod);
                $class = FinancialCategoryClass::SYNTHETIC;
            }

            $costCenter->code = $code;
            $costCenter->class = $class;
            $costCenter->save();
        });

        return redirect()->route('financialcategories.index')
            ->withStatus('Registro criado com sucesso.');
    }

    public function show($id)
    {
        $item = FinancialCategory::findOrFail($id);

        return view('financialcategories.show', compact('item'));
    }

    public function edit($id)
    {
        $item = FinancialCategory::findOrFail($id);
        $categories = $this->getPlanOptions();
        $data = $item->parent_id;

        return view('financialcategories.edit', compact(
            'item',
            'categories',
            'data',
        ));
    }

    public function update(Request $request, $id)
    {
        $item = FinancialCategory::findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $item->getKey())
        )->validate();

        DB::transaction(function () use ($request, $item) {
            $item->fill($request->all())->save();
        });


        return redirect()->route('financialcategories.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = FinancialCategory::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('financialcategories.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('financialcategories.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'description' => ['required', 'string', 'max:40'],
            'type' => ['required', new Enum(FinancialCategoryType::class)],
            'status' => ['required', new Enum(Status::class)],
            'parent_id' => ['nullable'],
            'descriptive' => ['nullable', 'string', 'max:200'],
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
            $options[$item->getKey()] = $item->code . ' - ' . $item->description;
        }
        return $options;
    }

    protected function getPlanOptions($except = null)
    {
        /** @var \Kalnoy\Nestedset\QueryBuilder $query */

        $query = FinancialCategory::select('id', 'description', 'code')->withDepth();
        if ($except) {
            $query->whereNotDescendantOf($except)->where('id', '<>', $except->id);
        }
        return $this->makeOptions($query->get());
    }
}
