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
        {!! Form::select('is_active', 'Ativo', activeOptions())->attrs(['class' => 'select2'])->required() !!}
      </div>
      <div class="col-md-3">
        {!! Form::select(
            'nutritional_classification',
            'Class. Nutricional',
            \App\Enums\NutritionalClassification::all()->prepend('Selecione...', ''),
        )->attrs(['class' => 'select2'])->required() !!}
      </div>
      <div class="col-md-9">
        <x-select-ncm :value="!empty($item->ncm_id) ? [$item->ncm_id => $item->ncm->description] : []" />
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
