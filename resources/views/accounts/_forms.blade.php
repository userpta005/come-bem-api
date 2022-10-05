<div class="row">
  <div class="col-md-3">
    {!! Form::text('balance', 'Saldo')->attrs(['class' => 'money'])->required() !!}
  </div>
  <div class="col-md-3">
    {!! Form::text('daily_limit', 'Limite Diário')->attrs(['class' => 'money'])->required() !!}
  </div>
  <div class="col-md-3">
    {!! Form::text('class', 'Turma')->attrs(['maxlength' => 10])->required(false) !!}
  </div>
  <div class="col-md-3">
    {!! Form::text('school_year', 'Série')->attrs(['maxlength' => 10])->required(false) !!}
  </div>
  <div class="col-md-3">
    {!! Form::select('status', 'Status', \App\Enums\Common\Status::all())->attrs(['class' => 'select2'])->required() !!}
  </div>
</div>
<div class="row">
  <div class="col-12">
    <button type="submit"
      class="btn btn-success float-right mt-4">Salvar</button>
  </div>
</div>