<div class="row">
  <div class="col-md-4">
    {!! Form::text('name', 'Nome')->attrs(['maxlength' => 20])->required() !!}
  </div>
  <div class="col-md-4">
    {!! Form::select('status', 'Status', \App\Enums\Common\Status::all())->attrs(['class' => 'select2'])->required() !!}
  </div>
  <div class="col-md-4">
    {!! Form::select('store_id', 'Loja')->options($stores->prepend('Selecione', ''))->attrs(['class' => 'select2']) !!}
  </div>
</div>
<div class="row">
  <div class="col-12">
    <button type="submit"
      class="btn btn-success float-right mt-4">Salvar</button>
  </div>
</div>
