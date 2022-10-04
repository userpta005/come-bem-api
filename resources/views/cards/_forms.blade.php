<div class="row">
  <div class="col-md-8">
    {!! Form::text('uuid', 'UUID')->attrs(['maxlength' => 100])->required() !!}
  </div>

  <div class="col-md-4">
    {!! Form::select('status', 'Status', \App\Enums\Common\Status::all())->attrs(['class' => 'select2'])->required() !!}
  </div>
</div>
<div class="row">
  <div class="col-12">
    <button type="submit"
      class="btn btn-success float-right mt-4">Salvar</button>
  </div>
</div>
