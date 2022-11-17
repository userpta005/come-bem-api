<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StoreStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\Store;
use App\Models\User;
use App\Rules\CpfCnpj;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\PersonRules;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:stores_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:stores_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:stores_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:stores_delete', ['only' => ['destroy']]);
        $this->middleware('tenant', ['only' => ['create', 'store', 'edit', 'update']]);
    }

    public function index(Request $request)
    {
        $data = Store::person()
            ->with(['tenant' => function ($query) {
                $query->person();
            }])
            ->when(session()->exists('tenant'), function ($query) {
                $query->where('tenant_id', session('tenant')['id']);
            })
            ->when(!empty($request->search), function ($query) use ($request) {
                $query->whereHas('people', function ($query) use ($request) {
                    $query->where('id', $request->search);
                });
            })
            ->when(!empty($request->status), function ($query) use ($request) {
                $query->where('stores.status', $request->status);
            })
            ->when(!empty($request->date_start), function ($query) use ($request) {
                $query->whereDate('stores.created_at', '>=', $request->date_start);
            })
            ->when(!empty($request->date_end), function ($query) use ($request) {
                $query->whereDate('stores.created_at', '<=', $request->date_end);
            })
            ->paginate(10);

        $stores = Store::person()->get();

        return view('stores.index', compact('data', 'stores'));
    }

    public function create()
    {
        return view('stores.create');
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

            $inputs['tenant_id'] = session('tenant')['id'];
            $inputs['person_id'] = $person->id;
            $store = Store::updateOrCreate(
                ['person_id' => $person->id],
                $inputs
            );

            $user = User::find(auth()->id());

            $user->stores()->attach($store->id);

            $user->load(['stores' => function ($query) {
                $query->person();
            }]);

            session(['stores' => $user->stores->toArray()]);

            if (!session()->exists('store')) {
                session(['store' => $user->stores[0]->toArray()]);
            }
        });

        return redirect()->route('stores.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show($id)
    {
        $item = Store::person()->findOrFail($id);

        return view('stores.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Store::person()->findOrFail($id);

        return view('stores.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Store::findOrFail($id);

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

        return redirect()->route('stores.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = Store::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('stores.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('stores.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    public function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'status' => ['required', new Enum(StoreStatus::class)],
            'nif' => ['required', 'max:14', new CpfCnpj, Rule::unique('people')->ignore($primaryKey)],
            'name' => ['required', 'max:100'],
            'full_name' => ['nullable', 'max:100'],
            'state_registration' => ['nullable', 'max:25'],
            'city_registration' => ['nullable', 'max:25'],
            'birthdate' => ['required', 'date'],
            'status' => ['required', new Enum(Status::class)],
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
