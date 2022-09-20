<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Person;
use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Rules\CpfCnpj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:users_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = User::person()
            ->when(!empty($request->search), function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    return $query->where('users.name', 'LIKE', "%$request->search%")
                        ->orWhere('users.email', 'LIKE', "%$request->search%")
                        ->orWhere('people.nif', 'LIKE', "%$request->search%");
                });
            })
            ->when(!empty($request->start_date), function ($query) use ($request) {
                return $query->whereDate('users.created_at', $request->start_date);
            })
            ->orderBy('users.name')
            ->paginate(10);

        return view('users.index', compact('data'));
    }

    public function create()
    {
        $roles = Role::orderBy('description')
            ->get();

        return view('users.create', compact('roles'));
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

            $inputs['person_id'] = $person->id;
            $inputs['password'] = bcrypt($inputs['password']);

            $user = User::updateOrCreate(
                ['person_id' => $inputs['person_id']],
                $inputs
            );

            $user->roles()->sync($request->role);
        });

        return redirect()->route('users.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show($id)
    {
        $item = User::person()->with('roles')->findOrFail($id);

        return view('users.show', compact('item'));
    }

    public function edit($id)
    {
        $roles = Role::orderBy('description')
            ->get();

        $item = User::person()->with('roles')->findOrFail($id);

        return view('users.edit', compact(
            'item',
            'roles'
        ));
    }

    public function update(Request $request, $id)
    {
        $item = User::findOrFail($id);

        $this->validate(
            $request,
            $this->rules($request, $item->person_id)
        );

        DB::transaction(function () use ($request, $item) {
            $inputs = $request->all();
            
            $person = Person::findOrFail($item->person_id);
            $person->fill($inputs)->save();
            $item->fill($inputs)->save();
            $item->roles()->sync($request->role);
        });

        return redirect()->route('users.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = User::findOrFail($id);

        if (auth()->id() != $item->id) {
            try {
                $item->delete();
                return redirect()->route('users.index')
                    ->withStatus('Registro deletado com sucesso.');
            } catch (\Exception $e) {
                return redirect()->route('user.index')
                    ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
            }
        } else {
            return redirect()->route('users.index')
                ->withError('Você não tem permissão para excluir esse usuário.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'nif' => ['required', 'max:18', new CpfCnpj, Rule::unique('people')->ignore($primaryKey)],
            'email' => ['required', 'max:100', Rule::unique('people')->ignore($primaryKey)],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
