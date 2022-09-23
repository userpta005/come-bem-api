<div class="row">
    <div class="col-md-7">
        {!!Form::text('name', 'Nome')
        ->required()
        ->attrs(['maxlength' => 40])!!}
    </div>
    <div class="col-md-3">
        {!!Form::select('type', 'Tipo', ['' => 'Selecione'] + $types)
        ->required()
        !!}
    </div>
    <div class="col-md-2">
        {!!Form::select('is_enabled', 'Status', [ 1 => 'Sim', 0 => 'Não'])
        ->value(isset($item) ? $item->is_enabled : 1)
        ->required()
        !!}
    </div>
    <div class="col-md-12">
        {!!Form::text('descriptive', 'Descritivo')
        ->attrs(['maxlength' => 120])!!}
    </div>
    <div class="col-md-12">
        {!!Form::text('order', 'Ordem')
        ->attrs(['maxlength' => 10, 'oninput' => "this.value = this.value.replace(/[^0-9]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"])
        ->required()
        !!}
    </div>
    <div class="col-md-12">
        {!!Form::select('parent_id', 'Antecessor', $sections)
        ->value($data)
        ->required()
        ->readOnly()
        !!}
    </div>
</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>
