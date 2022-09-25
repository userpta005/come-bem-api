@extends('layouts.app', ['page' => 'Saldo Estoque', 'pageSlug' => 'stocks'])

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card ">
        <div class="card-header">
          <div class="row">
            <div class="col-md-8">
              <h4 class="card-title">Saldo Estoque</h4>
            </div>
          </div>
        </div>
        <div class="card-body">
          @include('alerts.success')
          @include('alerts.error')

          {!! Form::open()->fill(request()->all())->get() !!}
          <div class="row">
            <div class="col-md-5">
              {!! Form::select('product_id', 'Produto', $products->prepend('Selecione...', ''))->attrs(['class' => 'select2']) !!}
            </div>

            <div class="col-md-12 d-flex justify-content-end align-items-center">
              <button class="btn btn-sm btn-primary mr-1"
                style="font-size: 9px;"
                type="submit">
                <svg xmlns="http://www.w3.org/2000/svg"
                  width="9"
                  height="9"
                  fill="currentColor"
                  class="bi bi-funnel-fill"
                  viewBox="0 0 16 16">
                  <path
                    d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
                </svg>
                Filtrar
              </button>
              <a id="clear-filter"
                style="font-size: 9px;"
                class="btn btn-sm btn-danger"
                href="{{ route('stocks.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                  width="9"
                  height="9"
                  fill="currentColor"
                  class="bi bi-trash-fill"
                  viewBox="0 0 16 16">
                  <path
                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                </svg>
                Limpar
              </a>
            </div>

          </div>
          {!! Form::close() !!}

          <div class="table-responsive-md">
            <table class="table tablesorter table-striped">
              <caption>N. Registros: {{ $data->total() }}</caption>
              <thead class=" text-primary">
                <th scope="col">Produto</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Un. Medida</th>
                <th scope="col"
                  class="text-right">Ação</th>
              </thead>
              <tbody>
                @forelse ($data as $item)
                  <tr style="font-size: 12px;">
                    <td>{{ $item->info }}</td>
                    <td>{{ floatToMoney($item->quantity) }}</td>
                    <td>{{ $item->product->um->name }}</td>
                    <td class="text-right">
                      <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light"
                          href="#"
                          role="button"
                          data-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                          @can('stocks_view')
                            <a class="dropdown-item"
                              href="{{ route('stocks.show', ['product' => $item->product_id]) }}">Visualizar</a>
                          @endcan
                        </div>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="20"
                      style="text-align: center; font-size: 1.1em;">
                      Nenhuma informação cadastrada.
                    </td>
                  </tr>
                @endforelse

              </tbody>
            </table>
          </div>
        </div>
        <div style="overflow: auto"
          class="my-4 mx-3 row">
          <nav class="d-flex ml-auto"
            aria-label="...">
            {{ $data->appends(request()->all())->links() }}
          </nav>
        </div>
      </div>
    </div>
  </div>
@endsection
