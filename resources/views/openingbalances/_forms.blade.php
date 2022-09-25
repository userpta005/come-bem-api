<div class="row">
  <div class="col-md-5">
    <div class="form-group">
      <label for="product_id">Produto</label>
      <select name="product_id"
        id="product_id"
        class="form-control select2"
        required
        @if (!empty($item)) readonly @endif>
        <option value="">Selecione</option>
        @foreach ($products as $product)
          <option value="{{ $product->id }}"
            data-lot="{{ $product->has_lot }}"
            data-um="{{ $product->um->name }}"
            @selected(!empty($item) && $item->product_id === $product->id)>
            {{ $product->name }}
          </option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-2">
    <label for="um">Unidade de Medida</label>
    <input type="text"
      name="um"
      id="um"
      class="form-control"
      value="{{ $item->product->um->name ?? '' }}"
      disabled>
  </div>
  <div class="col-md-5">
    {!! Form::select('store_id', 'Armazém')->options($stores, 'info')->readonly(true)->attrs(['class' => 'select2'])->required() !!}
  </div>
  <div class="col-md-2">
    {!! Form::text('total_amount', 'Valor Total')->value(!empty($item->total_amount) ? floatToMoney($item->total_amount) : 0)->required()->attrs(['class' => 'money']) !!}
  </div>
  <div class="col-md-3">
    {!! Form::date('date', 'Data')->readonly(!empty($item))->max(today()->format('Y-m-d'))->required() !!}
  </div>
  <div class="col-md-2">
    {!! Form::text('quantity', 'Quantidade')->value(!empty($item->quantity) ? floatToMoney($item->quantity) : 0)->required()->attrs(['class' => 'money']) !!}
  </div>
  <div class="col-md-2">
    {!! Form::text('provider_lot', 'Lote Fornecedor')->readonly()->attrs(['maxlength' => 15]) !!}
  </div>
  <div class="col-md-3">
    {!! Form::date('expiration_date', 'Data de Validade')->readonly()->value(!empty($item->expiration_date) ? $item->expiration_date->format('Y-m-d') : null) !!}
  </div>
</div>
<div class="row">
  <div class="col-12">
    <button type="submit"
      class="btn btn-success float-right mt-4">Salvar</button>
  </div>
</div>
