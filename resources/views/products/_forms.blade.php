<div class="row">

  <div class="col-md-3">
    <x-img name="image"
      :value="$item->image_url ?? null" />
  </div>

  <div class="col-md-9">
    <div class="row">
      <div class="col-md-6">
        {!! Form::text('name', 'Nome')->required()->attrs(['maxlength' => 40]) !!}
      </div>
      <div class="col-md-4">
        {!! Form::select('section_id', 'Seção', $sections->prepend('Selecione...', ''))->attrs(['class' => 'select2'])->required() !!}
      </div>
      <div class="col-md-2">
        {!! Form::select('status', 'Status', \App\Enums\Common\Status::all())->attrs(['class' => 'select2'])->required() !!}
      </div>
      <div class="col-md-3">
        {!! Form::select(
            'nutritional_classification',
            'Class. Nutricional',
            \App\Enums\NutritionalClassification::all()->prepend('Selecione...', ''),
        )->attrs(['class' => 'select2'])->required() !!}
      </div>
      <div class="col-md-3">
        {!! Form::select('um_id', 'Un. Medida', $ums->prepend('Selecione...', ''))->attrs(['class' => 'select2'])->required() !!}
      </div>
      <div class="col-md-6">
        <x-select-ajax name="ncm_id"
          label="NCM"
          route="/api/v1/ncms"
          prop="description"
          :value="!empty($item->ncm_id) ? [$item->ncm_id => $item->ncm->description] : []"
          :required="true" />
      </div>
      <div class="col-md-3">
        {!! Form::select('has_lot', 'Tem Lote?', [0 => 'Não', 1 => 'Sim'])->attrs(['class' => 'select2'])->required() !!}
      </div>
      <div class="col-md-3">
        {!! Form::text('price', 'Preço')->required()->attrs(['class' => 'money'])->value(floatToMoney($item->price ?? 0)) !!}
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <button type="submit"
      class="btn btn-success float-right mt-4">Salvar</button>
  </div>
</div>
