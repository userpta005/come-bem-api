@extends('layouts.app', ['page' => 'Requisição do Estoque', 'pageSlug' => 'requisitions'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h4 class="card-title">Requisição do Estoque</h4>
              </div>
              <div class="col-md-4 text-right">
                <a href="{{ route('requisitions.index') }}"
                  class="btn btn-sm btn-primary">Voltar</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            {!! Form::open()->post()->route('requisitions.store')->multipart() !!}
            @include('requisitions._forms')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script>
    var body = document.querySelector('body');
    var input_stock = document.getElementById('inp-stock_id[]');
    var input_store = $('#inp-store_id');

    input_store.on('change', getProducts);
    input_store.trigger('change');

    $('table label').remove();

    $(document).on('change', '.stock_id', function() {
      value = $(this).val();
      quantity = $(this).closest('tr').find('.quantity');
      um = $(this).closest('tr').find('.um');

      var has_duplicated = false;

      $('.stock_id').not($(this)).each(function() {
        if ($(this).val() == value) {
          has_duplicated = true
        }
      })

      if (!has_duplicated) {
        quantity.attr("readonly", false);

        um.val($(this).find('option:selected').data("um"))
        quantity.val(floatToMoney(parseFloat($(this).find('option:selected').data("quantity"))))
        quantity.trigger('input');
      } else {
        swal(
          "Atenção!",
          "Produto já utilizado em outra linha.",
          "warning"
        );
        $(this).val('').trigger('change')
      }
    });

    $(document).on('change', '.quantity', function(event) {
      $stock = $(this).closest('tr').find('.stock_id');

      var max_quantity = parseFloat($stock.find('option:selected').data("quantity"));
      var quantity = moneyToFloat($(this).val());

      if (max_quantity < quantity) {
        swal({
          title: "Estoque disponível",
          text: "A quantidade disponível desse produto é: " + max_quantity,
          icon: "warning",
          buttons: true,
          dangerMode: true,
        });

        $(this).val(floatToMoney(max_quantity));
      }

      $(this).trigger('input');
    });

    function getProducts() {
      body.classList.toggle('loading');
      var store_id = input_store.val();

      fetch(getUrl() + '/api/v1/stocks?store=' + store_id + "&?has-quantity=true")
        .then(function(response) {
          return response.json()
        })
        .then(function(response) {
          removeOptions(input_stock)
          var stocks = response.data

          stocks.forEach(function(stock) {
            var opt = document.createElement('option');
            var description = stock.product;

            if (stock.lot) {
              description += ' - Lote: ' + stock.provider_lot;
            }

            opt.appendChild(document.createTextNode(description));
            opt.value = stock.id;

            opt.setAttribute('data-quantity', stock.quantity);
            opt.setAttribute('data-um', stock.um);

            input_stock.appendChild(opt);
          })
          body.classList.toggle('loading');
        })
    }

    function removeOptions(selectElement) {
      var i, L = selectElement.options.length - 1;
      for (i = L; i >= 1; i--) {
        selectElement.remove(i);
      }
    }
  </script>
@endpush
