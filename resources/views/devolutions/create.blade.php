@extends('layouts.app', ['page' => 'Devolução do Estoque', 'pageSlug' => 'devolutions'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h4 class="card-title">Devolução do Estoque</h4>
              </div>
              <div class="col-md-4 text-right">
                <a href="{{ route('devolutions.index') }}"
                  class="btn btn-sm btn-primary">Voltar</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            {!! Form::open()->post()->route('devolutions.store')->multipart() !!}
            @include('devolutions._forms')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script>
    var body = document.querySelector('body')
    var input_stock = document.getElementById('inp-stock_id[]')
    var input_store = $('#inp-store_id')

    input_store.on('change', getProducts)
    input_store.trigger('change')

    $('table label').remove()

    $(document).on('change', '.stock_id', function() {
      value = $(this).val()
      quantity = $(this).closest('tr').find('.quantity')
      um = $(this).closest('tr').find('.um')

      var has_duplicated = false

      $('.stock_id').not($(this)).each(function() {
        if ($(this).val() == value) {
          has_duplicated = true
        }
      })

      if (!has_duplicated) {
        quantity.attr("readonly", false)
        um.val($(this).find('option:selected').data("um"))
      } else {
        swal(
          "Atenção!",
          "Produto já utilizado em outra linha.",
          "warning"
        );
        $(this).val('').trigger('change')
      }
    });

    function getProducts() {
      body.classList.toggle('loading')
      var store_id = input_store.val()

      fetch(getUrl() + '/api/v1/stocks?store=' + store_id + "&?has-quantity=true")
        .then(function(response) {
          return response.json()
        })
        .then(function(response) {
          removeOptions(input_stock)
          var stocks = response.data

          stocks.forEach(function(stock) {
            var opt = document.createElement('option');
            var description = stock.product

            if (stock.lot) {
              description += ' - Lote: ' + stock.provider_lot
            }

            opt.appendChild(document.createTextNode(description))
            opt.value = stock.id

            opt.setAttribute('data-quantity', stock.quantity)
            opt.setAttribute('data-um', stock.um)

            input_stock.appendChild(opt)
          })
          body.classList.toggle('loading')
        })
    }

    function removeOptions(selectElement) {
      var i, L = selectElement.options.length - 1
      for (i = L; i >= 1; i--) {
        selectElement.remove(i)
      }
    }
  </script>
@endpush
