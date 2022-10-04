<div class="row">
  <div class="col-md-3">
    {!! Form::text('nif', 'CPF')->attrs(['class' => 'cpf'])->readonly(!empty($item->nif)) !!}
  </div>
  <div class="col-md-5">
    {!! Form::text('name', 'Nome Completo')->attrs(['class' => 'name'])->required() !!}
  </div>
  <div class="col-md-4">
    {!! Form::text('full_name', 'Nome Social')->attrs(['class' => 'full_name'])->required() !!}
  </div>
  <div class="col-md-3">
    {!! Form::date('birthdate', 'DT. Nascimento')->required() !!}
  </div>
  <div class="col-md-2">
    {!! Form::select('status', 'Status')->options(\App\Enums\Common\Status::all())->value($item->status->value ?? \App\Enums\Common\Status::ACTIVE)->attrs(['class' => 'select2'])->required() !!}
  </div>
  <div class="col-md-7">
    {!! Form::text('email', 'Email')->type('email')->attrs(['class' => 'email'])->readonly(!empty($item->email)) !!}
  </div>
  <div class="col-md-3">
    {!! Form::text('phone', 'Telefone')->attrs(['class' => 'phone'])->required() !!}
  </div>
  <div class="col-md-4">
    {!! Form::select('city_id', 'Cidade')->options(isset($item) ? [$item->city_id => $item->city] : [])->required() !!}
  </div>
</div>

<div class="row">
  <div class="col-12">
    <button type="submit"
      class="btn btn-success float-right mt-4">Salvar</button>
  </div>
</div>
