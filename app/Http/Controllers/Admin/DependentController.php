<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Common\Status;
use App\Enums\PeopleGender;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\Dependent;
use App\Rules\CpfCnpj;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class DependentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dependents_create', ['only' => ['create', 'client']]);
        $this->middleware('permission:dependents_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:dependents_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:dependents_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request, Client $client)
    {
        $data = Dependent::query()
            ->person()
            ->when(!empty($request->search), function ($query) use ($request) {
                $query->where('dependents.id', $request->search);
            })
            ->when(!empty($request->status), function ($query) use ($request) {
                $query->where('dependents.status', $request->status);
            })
            ->when(!empty($request->date_start), function ($query) use ($request) {
                $query->whereDate('dependents.created_at', '>=', $request->date_start);
            })
            ->when(!empty($request->date_end), function ($query) use ($request) {
                $query->whereDate('dependents.created_at', '<=', $request->date_end);
            })
            ->where('client_id', $client->id)
            ->paginate(10);

        $dependents = Dependent::query()->person()->get();

        return view('dependents.index', compact('data', 'dependents', 'client'));
    }

    public function create(Client $client)
    {
        return view('dependents.create', compact('client'));
    }

    public function store(Request $request, Client $client)
    {
        $person = null;
        if ($request->filled('nif') || $request->filled('email')) {
            $person = Person::query()
                ->when($request->filled('nif'), function ($query) use ($request) {
                    $query->where('nif',  $request->nif);
                })
                ->when($request->filled('email'), function ($query) use ($request) {
                    $query->where('email',  $request->email);
                })
                ->first();
        }

        Validator::make(
            $request->all(),
            $this->rules($request, $person->id ?? null)
        )->validate();

        DB::transaction(function () use ($request, $client, $person) {
            $inputs = $request->all();

            $person = Person::updateOrCreate(
                ['id' => $person->id ?? null],
                $inputs
            );

            $inputs['client_id'] = $client->id;
            $inputs['person_id'] = $person->id;

            Dependent::updateOrCreate(
                ['person_id' => $person->id],
                $inputs
            );
        });

        return redirect()->route('clients.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show(Client $client, $id)
    {
        $item = Dependent::person()->findOrFail($id);

        return view('dependents.show', compact('item', 'client'));
    }

    public function edit(Client $client, $id)
    {
        $item = Dependent::person()->findOrFail($id);

        return view('dependents.edit', compact('item', 'client'));
    }

    public function update(Request $request, Client $client, $id)
    {
        $item = Dependent::findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $item->person_id)
        )->validate();

        DB::transaction(function () use ($request, $item) {
            $inputs = $request->all();
            $person = Person::find($item->person_id);
            $person->fill($inputs)->save();
            $item->fill($inputs)->save();
        });

        return redirect()->route('clients.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy(Client $client, $id)
    {
        $item = Dependent::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('clients.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('clients.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    public function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'nif' => ['nullable', 'max:14', Rule::when($request->filled('nif'), function () use ($primaryKey) {
                return [
                    new CpfCnpj,
                    Rule::unique('people')->ignore($primaryKey)
                ];
            })],
            'name' => ['required', 'max:100'],
            'full_name' => ['required', 'max:100'],
            'state_registration' => ['nullable', 'max:25'],
            'city_registration' => ['nullable', 'max:25'],
            'birthdate' => ['required', 'date'],
            'status' => ['required', new Enum(Status::class)],
            'gender' => ['required', new Enum(PeopleGender::class)],
            'email' => ['nullable', 'max:100', Rule::unique('people')->ignore($primaryKey)],
            'phone' => ['nullable', 'max:11'],
            'city_id' => ['required', Rule::exists('cities', 'id')],
            'zip_code' => ['nullable', 'max:8'],
            'address' => ['nullable', 'max:50'],
            'district' => ['nullable', 'max:50'],
            'number' => ['nullable', 'max:4'],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
