<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Person;
use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Rules\CpfCnpj;
use App\Traits\PersonRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

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
                $query->whereHas('people', function ($query) use ($request) {
                    $query->where('id', $request->search);
                });
            })
            ->when(!empty($request->status), function ($query) use ($request) {
                $query->where('users.status', $request->status);
            })
            ->when(!empty($request->date_start), function ($query) use ($request) {
                $query->whereDate('users.created_at', '>=', $request->date_start);
            })
            ->when(!empty($request->date_end), function ($query) use ($request) {
                $query->whereDate('users.created_at', '<=', $request->date_end);
            })
            ->when(
                session()->exists('tenant'),
                function ($query) {
                    $query->where('users.id', '!=', 1)
                        ->where(function ($query) {
                            $query->when(session()->exists('store'), function ($query) {
                                $query->whereHas('stores', function ($query) {
                                    $query->where('store_id', session('store')['id']);
                                })
                                    ->orWhereDoesntHave('stores')
                                    ->whereDoesntHave('people.tenant');
                            }, fn ($query) => $query->whereDoesntHave('stores'));
                        });
                }
            )
            ->paginate(10);

        $users = User::person()->get();

        return view('users.index', compact('data', 'users'));
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
            $this->rules($request, $person->id ?? null, false, true)
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

            !session()->exists('store') or $user->stores()->attach(session('store')['id']);

            $user->roles()->sync($request->roles);
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
            $item->roles()->sync($request->roles);
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

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false, $create = false)
    {
        $rules = [
            'roles' => ['required'],
            'roles.*' => [Rule::exists('roles', 'id')],
            'nif' => ['nullable', 'max:14', new CpfCnpj, Rule::unique('people')->ignore($primaryKey)],
            'name' => ['required', 'max:100'],
            'full_name' => ['nullable', 'max:100'],
            'state_registration' => ['nullable', 'max:25'],
            'city_registration' => ['nullable', 'max:25'],
            'birthdate' => ['required', 'date'],
            'status' => ['required', new Enum(\App\Enums\Common\Status::class)],
            'email' => ['required', 'max:100', Rule::unique('people')->ignore($primaryKey)],
            'phone' => ['required', 'max:11'],
            'city_id' => ['required', Rule::exists('cities', 'id')],
            'zip_code' => ['required', 'max:8'],
            'address' => ['required', 'max:50'],
            'district' => ['nullable', 'max:50'],
            'number' => ['nullable', 'max:4'],
        ];

        if ($create) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        }

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
