<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ClientType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\Client;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\PersonRules;
use Illuminate\Validation\Rules\Enum;

class ClientController extends Controller
{
    use PersonRules;

    public function __construct()
    {
        $this->middleware('permission:clients_create', ['only' => ['create', 'client']]);
        $this->middleware('permission:clients_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:clients_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:clients_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = Client::person()
            ->when(!empty($request->search), function ($query) use ($request) {
                $query->where('clients.id', $request->search);
            })
            ->when(!empty($request->status), function ($query) use ($request) {
                $query->where('clients.status', $request->status);
            })
            ->when(!empty($request->date_start), function ($query) use ($request) {
                $query->whereDate('clients.created_at', '>=', $request->date_start);
            })
            ->when(!empty($request->date_end), function ($query) use ($request) {
                $query->whereDate('clients.created_at', '<=', $request->date_end);
            })
            ->paginate(10);

        $clients = Client::person()->get();

        return view('clients.index', compact('data', 'clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $person = Person::where('nif',  $request->nif)->first();

        Validator::make(
            $request->all(),
            $this->rules($request, $person->id ?? null)
        )->validate();

        DB::transaction(function () use ($request) {
            $inputs = $request->all();

            $person = Person::updateOrCreate(
                ['nif' => $inputs['nif']],
                $inputs
            );

            $inputs['person_id'] = $person->id;

            Client::updateOrCreate(
                ['person_id' => $person->id],
                $inputs
            );

            $user = User::updateOrCreate(
                ['person_id' => $inputs['person_id']],
                [
                    'name' => $inputs['name'],
                    'email' => $inputs['email'],
                    'password' => bcrypt(preg_replace("/\D+/", "", $inputs['nif'])),
                    'status' => $inputs['status']
                ]
            );

            Role::updateOrCreate(
                ['name' => 'client'],
                [
                    'description' => 'Cliente'
                ]
            );

            $user->assignRole('client');
        });

        return redirect()->route('clients.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show($id)
    {
        $item = Client::person()->findOrFail($id);

        return view('clients.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Client::person()->findOrFail($id);

        return view('clients.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Client::findOrFail($id);

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

    public function destroy($id)
    {
        $item = Client::findOrFail($id);

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
            'type' => ['required', 'integer', new Enum(ClientType::class)],
        ];

        $this->PersonRules($primaryKey);
        $rules = array_merge($rules, $this->rules);

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
