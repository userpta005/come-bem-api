<div class="row">
  <div class="col-12">
    <div class="painel-header">
      <div class="row">
        <div class="col-md-4">
          {!! Form::text('code', 'Código')->required()->readonly()->value(code(settings('mve'))) !!}
        </div>
        <div class="col-md-4">
          <input type="hidden"
            name="user_id"
            value="{{ auth()->id() }}">
          {!! Form::text('user', 'Usuário')->required()->readonly()->value(auth()->user()->people->info) !!}
        </div>
        <div class="col-md-4">
          {!! Form::text('created', 'Dt. Criação:')->required()->value(today()->format('d/m/Y'))->readonly() !!}
        </div>
        <div class="col-md-4">
          {!! Form::select('type', 'Tipo Movimentação', \App\Enums\StockMovementType::only([1]))->attrs(['class' => 'select2 '])->readonly()->required() !!}
        </div>
        <div class="col-md-8">
          {!! Form::select('store_id', 'Armazém')->options($stores, 'info')->attrs(['class' => 'select2 '])->readonly()->required() !!}
        </div>
      </div>
    </div>
    <div class="painel-body">
      <div class="row">
        <div class="table-responsive">
          <table id="tabela"
            class="table table-dynamic">
            <thead>
              <tr class="conteudo-th">
                <th scope="col"></th>
                <th scope="col"
                  class="big">Produto</th>
                <th scope="col">Un. Medida</th>
                <th scope="col">Qtde.</th>
              </tr>
            </thead>
            <tbody>
              <tr class="dynamic-form">
                <td>
                  <x-btn-remove-item />
                </td>
                <td>
                  {!! Form::select('stock_id[]', '', ['' => 'Selecione...'])->attrs(['class' => 'stock_id'])->required() !!}
                </td>
                <td>
                  {!! Form::text('um[]')->attrs(['class' => 'um'])->readonly()->required() !!}
                </td>
                <td>
                  {!! Form::text('quantity[]')->attrs(['class' => 'quantity'])->readonly()->required() !!}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-12">
            <x-btn-add-item />
          </div>
        </div>
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
