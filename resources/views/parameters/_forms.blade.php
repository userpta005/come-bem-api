<div class="row">
    <div class="col-md-6">
        {!!Form::text('name', 'Nome')
        ->attrs(['maxlength' => 20])
        ->required(true)
        ->readonly(true)
        !!}
    </div>
    <div class="col-md-6">
        {!!Form::text('description', 'Descrição')
        ->attrs(['maxlength' => 50])
        ->required(true)
        ->readonly(true)
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::select('type', 'Tipo')
        ->options(\App\Enums\ParameterType::all()->prepend('Selecione...', ''))
        ->attrs(['class' => 'select2'])
        ->required(true)
        !!}
    </div>
    <div class="col-md-3">
        {!!Form::text('value', 'Valor')
        ->attrs(['maxlength' => 20])
        ->required(true)
        !!}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>
