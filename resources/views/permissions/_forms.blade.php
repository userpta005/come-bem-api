<div class="row">
    <div class="col-md-4">
        {!!Form::text('name', 'Nome')
        ->attrs(['maxlength' => 15])
        ->required()
        !!}
    </div>
    <div class="col-md-8">
        {!!Form::text('description', 'Descrição')
        ->attrs(['maxlength' => 40])
        ->required()
        !!}
    </div>
</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>
