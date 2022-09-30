<div class="row">
  <div class="col-md-9">
    {!! Form::text('description', 'Descrição')->required()->attrs(['maxlength' => 40]) !!}
  </div>
  <div class="col-md-3">
    {!! Form::select('status', 'Status', \App\Enums\Common\Status::all())->attrs(['class' => 'select2'])->required() !!}
  </div>

  <div class="col-md-6">
    {!! Form::select('type', 'Tipo Categoria', \App\Enums\FinancialCategoryType::all()->prepend('Selecione...', ''))->required()->attrs(['class' => 'select2']) !!}
  </div>
  <div class="col-md-6">
    {!! Form::select('parent_id', 'Antecessor', $categories)->value($data)->attrs(['class' => 'select2'])->required(!empty($data))->readOnly() !!}
  </div>
  <div class="col-md-8">
    {!! Form::textarea('descriptive', 'Descritivo')->attrs([
        'maxlength' => 200,
        'rows' => 4,
        'style' => 'border: 1px rgba(29, 37, 59, 0.3) solid; border-radius: 0.4285rem;',
    ]) !!}
  </div>
</div>
<div class="row">
  <div class="col-12">
    <button type="submit"
      class="btn btn-success float-right mt-4">Salvar</button>
  </div>
</div>
