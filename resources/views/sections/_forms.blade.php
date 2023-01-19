<div class="row">
  <div class="col-md-3">
    <x-img name="image"
      :value="$item->image_url ?? null" />
  </div>
  <div class="col-md-9">
    <div class="row">
      <div class="col-md-8">
        {!! Form::text('name', 'Nome')->required()->attrs(['maxlength' => 40]) !!}
      </div>
      <div class="col-md-4">
        {!! Form::select('status', 'Status', \App\Enums\Common\Status::all())->attrs(['class' => 'select2'])->required() !!}
      </div>
      <div class="col-md-12">
        {!! Form::text('description', 'Descrição')->attrs(['maxlength' => 120]) !!}
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
