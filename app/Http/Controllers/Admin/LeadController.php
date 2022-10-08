<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Models\Person;
use App\Http\Controllers\Controller;
use App\Traits\PersonRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;

class LeadController extends Controller
{
    use PersonRules;

    public function __construct()
    {
        $this->middleware('permission:leads_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:leads_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:leads_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:leads_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = Lead::person()
            ->when(!empty($request->search), function ($query) use ($request) {
                $query->where('leads.id', $request->search);
            })
            ->when(!empty($request->status), function ($query) use ($request) {
                $query->where('leads.status', $request->status);
            })
            ->when(!empty($request->date_start), function ($query) use ($request) {
                $query->whereDate('leads.created_at', '>=', $request->date_start);
            })
            ->when(!empty($request->date_end), function ($query) use ($request) {
                $query->whereDate('leads.created_at', '<=', $request->date_end);
            })
            ->when(session()->exists('store'), function ($query) {
                $query->where('leads.store_id', session('store')['id']);
            })
            ->when(!session()->exists('store'), function ($query) {
                $query->whereNull('leads.store_id');
            })
            ->orderBy('people.name')
            ->paginate(10);

        return view('leads.index', compact('data'));
    }

    public function create()
    {
        return view('leads.create');
    }

    public function store(Request $request)
    {
        $person = Person::where('nif', $request->nif)->first();

        $this->validate(
            $request,
            $this->rules($request, $person->id ?? null)
        );

        DB::transaction(function () use ($request) {
            $inputs = $request->all();

            $person = Person::updateOrCreate(
                ['nif' => $inputs['nif']],
                $inputs
            );

            $inputs['store_id'] = session()->exists('store') ? session('store')['id'] : null;
            $inputs['person_id'] = $person->id;

            Lead::updateOrCreate(
                ['person_id' => $inputs['person_id']],
                $inputs
            );
        });

        return redirect()->route('leads.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show($id)
    {
        $item = Lead::person()->findOrFail($id);

        return view('leads.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Lead::person()->findOrFail($id);

        return view('leads.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Lead::findOrFail($id);

        $this->validate(
            $request,
            $this->rules($request, $item->person_id)
        );

        DB::transaction(function () use ($request, $item) {
            $inputs = $request->all();

            $person = Person::findOrFail($item->person_id);
            $person->fill($inputs)->save();
            $item->fill($inputs)->save();
        });

        return redirect()->route('leads.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = Lead::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('leads.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('leads.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'status' => ['required', new Enum(LeadStatus::class)],
            'observation' => ['nullable', 'max:191']
        ];

        $this->personRules($primaryKey);
        $rules = array_merge($rules, $this->rules);

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
