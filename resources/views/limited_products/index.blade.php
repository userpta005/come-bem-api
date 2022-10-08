@extends('layouts.app', ['page' => 'Restrição de Produtos', 'pageSlug' => 'limited_products'])

@push('css')
  <style>
    .group-main {
      list-style: none;
    }

    .group-flex {
      display: flex;
      flex-wrap: wrap;
    }

    .group-title {
      cursor: pointer;
      user-select: none;
    }

    input:checked {
      accent-color: grey;
    }

    .square {
      width: 12px;
      height: 12px;
      border: 2px;
      display: inline-block;
    }
  </style>
@endpush

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card ">
        <div class="card-header">
          <div class="row">
            <div class="col-8">
              <h4 class="card-title">Restrição de Produtos</h4>
            </div>
            @can('clients_view')
              <div class="col-md-4 text-right">
                <a href="{{ route('clients.index') }}"
                  class="btn btn-sm btn-primary">Voltar</a>
              </div>
            @endcan
          </div>
        </div>

        <div class="card-body">
          @include('alerts.success')
          @include('alerts.error')

          <div class="row">
            <div class="col-md-12 text-left">
              <p style="font-size:15px;">
                <b>Classificação Nutricional:</b>
                <span style="margin-right: 10px;">
                  <span class="square"
                    style="background-color:#808080;"></span>
                  Em Análise
                </span>
                <span style="margin-right: 10px;">
                  <span class="square"
                    style="background-color:#ff0000;"></span>
                  Pouco Nutritivo
                </span>
                <span style="margin-right: 10px;">
                  <span class="square"
                    style="background-color: #ffa500;"></span>
                  Moderado
                </span>
                <span style="margin-right: 10px;">
                  <span class="square"
                    style="background-color: #008000;"></span>
                  Muito Nutritivo
                </span>
              </p>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="treeview border rounded p-3">
                  <ul class="group-main p-0 m-0 group-flex justify-content-between">
                    @foreach ($data as $item)
                      <li class="group-item my-1 mr-2"
                        style="min-width: 200px;">
                        <input type="checkbox"
                          id="product-{{ $item->id }}"
                          data-product="{{ $item }}"
                          @checked($limitedProducts->where('product_id', $item->id)->where('account_id', $account->id)->first())>
                        <span class="group-title">
                          @if ($item->nutritional_classification->isUnder_Analysis())
                            <span class="square ml-1"
                              style="background-color: #808080;"></span>
                          @elseif ($item->nutritional_classification->isLittle_Nutritious())
                            <span class="square ml-1"
                              style="background-color: #ff0000;"></span>
                          @elseif ($item->nutritional_classification->isModerate())
                            <span class="square ml-1"
                              style="background-color: #ffa500;"></span>
                          @elseif ($item->nutritional_classification->isVery_Nutritious())
                            <span class="square ml-1"
                              style="background-color: #008000;"></span>
                          @endif
                          {{ $item->name }}
                        </span>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <button type="button"
                class="btn btn-success float-right mt-4">Salvar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script>
    $('button[type=button]').on('click', function() {
      var products = $('.group-main').find('input[type=checkbox]');
      var arrProducts = [];
      products.each(function() {
        let prod = $(this).data('product');
        arrProducts.push({
          product_id: prod.id,
          account_id: {{ $account->id }},
          is_checked: $(this).prop('checked'),
        });
      })

      $.ajax({
          method: "PUT",
          url: getUrl() + "/api/v1/limited_products",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
            products: arrProducts
          }
        })
        .done(function(rsp) {
          swal('Sucesso !', rsp.message, 'success')
        })
        .fail(function(rsp) {
          swal('Erro !', rsp.responseJSON.message, 'error')
        });
    });
  </script>
@endpush
