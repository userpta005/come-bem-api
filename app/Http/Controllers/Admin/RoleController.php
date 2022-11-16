<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Rule as ModelsRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:roles_delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data =  Role::orderBy('description')->paginate(10);

        return view('roles.index', compact('data'));
    }

    public function create()
    {
        $permissions = Permission::defaultPermissions();

        $rules = ModelsRule::orderBy('name')->get();

        if (session()->has('tenant')) {
            /** @var User $user */
            $user = auth()->user();

            $userPermissions = $user->getPermissionsViaRoles()->pluck('name')->all();

            $permissions = $this->checkPermissionsTenant($permissions, $userPermissions);
        }

        return view('roles.create', compact('permissions', 'rules'));
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            $this->rules($request)
        );

        DB::transaction(function () use ($request) {
            $item = Role::create($request->except('permissions'));

            $item->givePermissionTo($request->permissions);

            if (!empty($request->rules)) {
                $item->rules()->attach($request->rules);
            }
        });

        return redirect()->route('roles.index')
            ->withStatus('Registro criado com sucesso.');
    }

    public function show($id)
    {
        $item = Role::findOrFail($id);

        return view('roles.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Role::findOrFail($id);

        $permissions = Permission::defaultPermissions();

        if (session()->has('tenant')) {
            /** @var User $user */
            $user = auth()->user();

            $userPermissions = $user->getPermissionsViaRoles()->pluck('name')->all();

            $permissions = $this->checkPermissionsTenant($permissions, $userPermissions);
        }

        $rules = ModelsRule::orderBy('name')->get();

        $rulesRole = $item->rules->modelKeys();

        return view('roles.edit', compact('item', 'permissions', 'rules', 'rulesRole'));
    }

    public function update(Request $request, $id)
    {
        $item = Role::findOrFail($id);

        $this->validate(
            $request,
            $this->rules($request, $item->id)
        );

        DB::transaction(function () use ($item, $request) {
            $item->fill($request->except('permissions'))->save();
            $item->permissions()->detach();
            $item->givePermissionTo($request->permissions);

            $rules = [];
            if (!empty($request->rules)) {
                $rules = $request->rules;
            }
            $item->rules()->sync($rules);
        });

        return redirect()->route('roles.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = Role::with('permissions', 'users')->findOrFail($id);

        try {
            if ($item->permissions->isNotEmpty()) {
                return redirect()->route('roles.index')
                    ->withError('Não foi possível deletar pois existem permissões vinculadas a esta atribuição.');
            } else if ($item->users->isNotEmpty()) {
                return redirect()->route('roles.index')
                    ->withError('Não foi possível deletar pois existem usuários vinculados a esta atribuição.');
            }

            $item->delete();
            return redirect()->route('roles.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    private function checkPermissionsTenant(array &$permissions, array $userPermissions)
    {
        foreach ($permissions as  $key => &$value) {
            if (!is_array($value) && !empty($value)) {
                return;
            }

            if (
                array_key_exists('title', $value) &&
                !empty($value['items'])
            ) {
                $value['items'] = $this->checkPermissionsTenant($value['items'], $userPermissions);
            }

            if (
                array_key_exists('title', $value) &&
                empty($value['items'])
            ) {
                unset($permissions[$key]);
            }

            if (
                array_key_exists('name', $value) &&
                !in_array($value['name'], $userPermissions)
            ) {
                unset($permissions[$key]);
            }
        }
        return $permissions;
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'max:40', Rule::unique('roles')->ignore($primaryKey)],
            'description' => ['required', 'max:40'],
            'permissions' => ['nullable']
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
