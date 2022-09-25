<div class="row">
  <div class="col-md-7">
    {!! Form::text('name', 'Nome')->required()->attrs(['maxlength' => 40]) !!}
  </div>
  <div class="col-md-3">
    {!! Form::select('type', 'Tipo', $types->prepend('Selecione...', ''))->attrs(['class' => 'select2'])->required() !!}
  </div>
  <div class="col-md-2">
    {!! Form::select('status', 'Status', \App\Enums\Common\Status::all())->attrs(['class' => 'select2'])->required() !!}
  </div>
  <div class="col-md-12">
    {!! Form::text('description', 'Descrição')->attrs(['maxlength' => 120]) !!}
  </div>
  <div class="col-md-4">
    {!! Form::text('order', 'Ordem')->attrs([
            'maxlength' => 10,
            'oninput' =>
                "this.value = this.value.replace(/[^0-9]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');",
        ])->required() !!}
  </div>
  <div class="col-md-8">
    {!! Form::select('parent_id', 'Antecessor', $sections)->value($data)->attrs(['class' => 'select2'])->required(!empty($data))->readonly() !!}
  </div>
</div>
<div class="row">
  <div class="col-12">
    <button type="submit"
      class="btn btn-success float-right mt-4">Salvar</button>
  </div>
</div>
