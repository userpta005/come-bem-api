<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StoreStatus;
use App\Enums\StoreType;
use App\Enums\TenantDueDays;
use App\Enums\TenantSignature;
use App\Enums\TenantStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\Product;
use App\Models\Section;
use App\Models\Store;
use App\Models\Tenant;
use App\Rules\CpfCnpj;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
        $tenants = Tenant::person()
            ->where('tenants.status', TenantStatus::ENABLED)
            ->get();

        return view('stores.create', compact('tenants'));
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
            $inputs['lending_value'] = moneyToFloat($inputs['lending_value']);
            $inputs['pix_rate'] = moneyToFloat($inputs['pix_rate']);
            $inputs['card_rate'] = moneyToFloat($inputs['card_rate']);

            $tenant = Tenant::query()
                ->with(['people.user'])
                ->findOrFail($inputs['tenant_id']);

            $person = Person::updateOrCreate(
                ['nif' => $inputs['nif']],
                $inputs
            );

            $inputs['person_id'] = $person->id;
            $inputs['app_token'] = uniqid();
            $store = Store::updateOrCreate(
                ['person_id' => $person->id],
                $inputs
            );

            $user = $tenant->people->user;

            $user->stores()->attach($store->id);

            if (!empty($inputs['replicate_products'])) {
                $sections = Section::query()->whereNull('store_id')->get();
                $products = Product::query()->wherenull('store_id')->get();
                $now = now()->format('Y-m-d H:m:s');

                $sections = $sections->map(function ($section) use ($store, $now) {
                    $section = $section->toArray();
                    $section['store_id'] = $store->id;
                    $section['created_at'] = $now;
                    $section['updated_at'] = $now;
                    unset($section['id']);
                    unset($section['image_url']);
                    return $section;
                });

                $products = $products->map(function ($product) use ($store, $now) {
                    $product = $product->toArray();
                    $product['store_id'] = $store->id;
                    $product['created_at'] = $now;
                    $product['updated_at'] = $now;
                    unset($product['id']);
                    unset($product['image_url']);
                    return $product;
                });

                DB::table('sections')->insert($sections->toArray());
                DB::table('products')->insert($products->toArray());
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

        $tenants = Tenant::person()
            ->where('tenants.status', TenantStatus::ENABLED)
            ->get();

        return view('stores.edit', compact('item', 'tenants'));
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
            $item->users()->detach();
            $item->delete();
            return redirect()->route('stores.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            $msg = 'Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.';
            return redirect()->route('stores.index')
                ->withError($msg);
        }
    }

    public function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'tenant_id' => ['required', Rule::exists('tenants', 'id')],
            'status' => ['required', new Enum(StoreStatus::class)],
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


            'latitude' => ['required', 'max:60'],
            'longitude' => ['required', 'max:60'],
            'pix_key' => ['required', 'max:60'],
            'bank' => ['required', 'max:20'],
            'agency' => ['required', 'max:20'],
            'account' => ['required', 'max:20'],
            'whatsapp' => ['required', 'max:11'],
            'type' => ['required', new Enum(StoreType::class)],
            'email_digital' => ['required', 'max:100'],
            'signature' => ['required', new Enum(TenantSignature::class)],
            'dt_accession' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'due_day' => ['required', new Enum(TenantDueDays::class)],
            'number_equipment' => ['required', 'max:100'],
            'lending_value' => ['required'],
            'pix_rate' => ['required'],
            'card_rate' => ['required'],
            'observation' => ['required', 'max:100'],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
