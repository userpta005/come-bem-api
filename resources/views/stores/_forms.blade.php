<div class="row">
    <div class="col-md-3">
        {!!Form::text('nif', 'CPF/CNPJ')
        ->attrs(['class' => 'cpf_cnpj'])
        ->readonly(isset($item))
        ->required()
        !!}
    </div>
    <div class="col-md-5">
        {!!Form::text('name', 'Nome Completo/Razão Social')
        ->attrs(['class' => 'name'])
        ->required()
        !!}
    </div>
    <div class="col-md-4">
        {!!Form::text('full_name', 'Nome Social/Nome Fantasia')
        ->attrs(['class' => 'full_name'])
        ->required()
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::text('state_registration', 'Inscrição Estadual')
        ->attrs(['class' => 'state_registration'])
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::text('city_registration', 'Inscrição Municipal')
        ->attrs(['class' => 'city_registration'])
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::date('birthdate', 'DT. Nasc/Abertura')
        ->required()
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::select('status', 'Status')
        ->options(\App\Enums\StoreStatus::all()->prepend('Selecione...', ''))
        ->value(isset($item) ? $item->status->value : \App\Enums\StoreStatus::ENABLED)
        ->attrs(['class' => 'select2'])
        ->required()
        !!}
    </div>
    <div class="col-md-5">
        {!!Form::text('email', 'Email')
        ->type('email')
        ->attrs(['class' => 'email'])
        ->readonly(isset($item))
        ->required()
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::text('phone', 'Telefone')
        ->attrs(['class' => 'phone'])
        ->required()
        !!}
    </div>
    <div class="col-md-4">
        {!!Form::select('city_id', 'Cidade')
        ->options(isset($item) ? [$item->city_id => $item->city ] : [])
        ->required()
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::text('zip_code', 'CEP')
        ->attrs(['class' => 'cep'])
        ->required()
        !!}
    </div>
    <div class="col-md-5">
        {!!Form::text('address', 'Endereço')
        ->attrs(['class' => 'address'])
        ->required()
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::text('district', 'Bairro')
        ->attrs(['class' => 'district'])
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::text('number', 'Número')
        ->attrs(['class' => 'number'])
        !!}
    </div>
</div>

<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>