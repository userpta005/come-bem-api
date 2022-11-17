<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Common\Status;
use App\Enums\TenantDueDays;
use App\Enums\TenantSignature;
use App\Enums\TenantStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Person;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Rules\CpfCnpj;
use App\Traits\PersonRules;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class TenantController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:tenants_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:tenants_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:tenants_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:tenants_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = Tenant::person()
            ->when(!empty($request->search), function ($query) use ($request) {
                $query->whereHas('people', function ($query) use ($request) {
                    $query->where('id', $request->search);
                });
            })
            ->when(!empty($request->status), function ($query) use ($request) {
                $query->where('tenants.status', $request->status);
            })
            ->when(!empty($request->signature), function ($query) use ($request) {
                $query->where('tenants.signature', $request->signature);
            })
            ->when(!empty($request->due_day), function ($query) use ($request) {
                $query->where('tenants.due_day', $request->due_day);
            })
            ->when(!empty($request->dt_accession_start), function ($query) use ($request) {
                $query->whereDate('tenants.dt_accession', '>=', $request->dt_accession_start);
            })
            ->when(!empty($request->dt_accession_end), function ($query) use ($request) {
                $query->whereDate('tenants.dt_accession', '<=', $request->dt_accession_end);
            })
            ->when(!empty($request->due_date_start), function ($query) use ($request) {
                $query->whereDate('tenants.due_date', '>=', $request->due_date_start);
            })
            ->when(!empty($request->due_date_end), function ($query) use ($request) {
                $query->whereDate('tenants.due_date', '<=', $request->due_date_end);
            })
            ->orderBy('people.name')
            ->paginate(10);

        $tenants = Tenant::person()->get();

        return view('tenants.index', compact('data', 'tenants'));
    }

    public function create()
    {
        return view('tenants.create');
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
            $inputs['value'] = moneyToFloat($inputs['value']);

            $person = Person::updateOrCreate(
                ['nif' => $inputs['nif']],
                $inputs
            );

            $inputs['person_id'] = $person->id;

            Tenant::updateOrCreate(
                ['person_id' => $person->id],
                $inputs
            );

            $user = User::updateOrCreate(
                ['person_id' => $inputs['person_id']],
                [
                    'name' => $inputs['name'],
                    'email' => $inputs['email'],
                    'password' => bcrypt(preg_replace("/\D+/", "", $inputs['nif'])),
                    'status' => TenantStatus::from($inputs['status'])->isEnabled() ? Status::ACTIVE : Status::INACTIVE
                ]
            );

            Role::updateOrCreate(
                ['name' => 'contractor'],
                [
                    'description' => 'Contratante'
                ]
            );

            $user->assignRole('contractor');
        });

        return redirect()->route('tenants.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show($id)
    {
        $item = Tenant::person()->findOrFail($id);

        return view('tenants.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Tenant::person()->findOrFail($id);

        return view('tenants.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Tenant::findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $item->person_id)
        )->validate();

        DB::transaction(function () use ($request, $item) {
            $inputs = $request->all();

            $person = Person::find($item->person_id);
            $person->fill($inputs)->save();

            $inputs['value'] = moneyToFloat($inputs['value']);
            $item->fill($inputs)->save();

            $user = User::where('person_id', $item->person_id)->first();

            if ($user instanceof User) {
                $user->status =  $inputs['status'] == TenantStatus::ENABLED->value ? Status::ACTIVE : Status::INACTIVE;
                $user->save();
            }
        });

        return redirect()->route('tenants.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = Tenant::findOrFail($id);

        try {
            $item->delete();

            return redirect()->route('tenants.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('tenants.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }


    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'status' => ['required', new Enum(TenantStatus::class)],
            'cellphone' => ['nullable', 'max:11'],
            'contact' => ['nullable', 'max:11'],
            'contact_phone' => ['nullable', 'max:11'],
            'signature' => ['required', new Enum(TenantSignature::class)],
            'dt_accession' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'due_day' => ['required', new Enum(TenantDueDays::class)],
            'value' => ['required'],
            'nif' => ['required', 'max:14', new CpfCnpj, Rule::unique('people')->ignore($primaryKey)],
            'name' => ['required', 'max:100'],
            'full_name' => ['nullable', 'max:100'],
            'state_registration' => ['nullable', 'max:25'],
            'city_registration' => ['nullable', 'max:25'],
            'birthdate' => ['required', 'date'],
            'email' => ['required', 'max:100', Rule::unique('people')->ignore($primaryKey)],
            'phone' => ['required', 'max:11'],
            'city_id' => ['required', Rule::exists('cities', 'id')],
            'zip_code' => ['required', 'max:8'],
            'address' => ['required', 'max:50'],
            'district' => ['nullable', 'max:50'],
            'number' => ['nullable', 'max:4'],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
