<div class="row">

  <div class="col-md-3">
    <x-img name="icon"
      :value="$item->icon_url" />
  </div>

  <div class="col-md-9">
    <div class="row">
      <div class="col-md-3">
        {!! Form::text('code', 'Código')->required()->attrs(['maxlength' => 20]) !!}
      </div>
      <div class="col-md-6">
        {!! Form::text('name', 'Nome')->required()->attrs(['maxlength' => 30]) !!}
      </div>
      <div class="col-md-3">
        {!! Form::select('status', 'Status', \App\Enums\Common\Status::all())->attrs(['class' => 'select2']) ->required() !!}
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
