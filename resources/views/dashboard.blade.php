@extends('layouts.app', ['page' => 'Dashboard', 'pageSlug' => 'dashboard'])

@push('css')
  <style>
    .card {
      border-top-left-radius: 0px;
    }
  </style>
@endpush

@section('content')
  <div class="row">
    <div class="col-12 d-flex justify-content-between align-items-center">
      @push('css')
        <style>
          .nav-item-custom {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px 10px 0 0;
            box-shadow: 0 1px 15px 0 rgba(0, 0, 0, 0.05);
          }

          .nav-link-custom {
            color: black;
            font-weight: 500;
          }

          .active-custom {
            background: white;
          }
        </style>
      @endpush
      <ul class="nav">
        <li class="nav-item nav-item-custom home">
          <a class="nav-link nav-link-custom"
            href="{{ route('home') }}">Painel Administrativo</a>
        </li>
        <li class="nav-item nav-item-custom pdv">
          <a class="nav-link nav-link-custom"
            href="{{ route('pdv') }}">PDV/Pedidos</a>
        </li>
      </ul>
      <span class="openedCashier d-flex align-items-center"
        style="font-size:medium; color: black; font-weight: 600;">
        {{ session('openedCashier') ? session('cashier')['description'] . ' aberto' : 'Caixa fechado' }}
        <span class="ml-2"
          style="background-color: {{ session('openedCashier') ? '#92e18b' : '#e18b8b' }}; height: 20px; width: 20px; border-radius: 50%;">
        </span>
      </span>
      @push('js')
        <script>
          const lastPart = window.location.href.split('/').pop().split('?')[0]
          const element = document.querySelector(`.${lastPart}`)
          element.classList.add("active-custom")
        </script>
      @endpush
    </div>
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          @include('alerts.success')
          @include('alerts.error')
          <div class="">
            {!! Form::open()->fill(request()->all())->get() !!}
            <div class="row">
              <div class="col-md-auto">
                <p>Turno</p>
                @foreach ($turns as $index => $element)
                  <input type="checkbox"
                    name="turns[]"
                    value="{{ $index }}"
                    @if (in_array($index, $filterTurns)) checked @endif
                    onChange="this.form.submit()">
                  <span style="padding-right: 20px">{{ $element }}</span>
                @endforeach
              </div>
              <div class="col-md-3">
                {!! Form::date('date', 'Data')->value($date) !!}
              </div>
              <div class="col-md-2 d-flex justify-content-center align-items-center">
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
                  href="{{ route('home') }}">
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
            <div class="row d-flex justify-content-center">
              <div class="col-md-12">
                <p><b>Legenda:</b>
                  @foreach ($status as $index => $element)
                    <input type="checkbox"
                      name="status[]"
                      value="{{ $index }}"
                      @if (in_array($index, $filterStatus)) checked @endif
                      onChange="this.form.submit()">
                    <i class="fa fa-square mr-1"
                      aria-hidden="true"
                      style="color: {{ \App\Enums\OrderStatus::colors()[$index] }}"></i>
                    <span style="padding-right: 20px">{{ $element }}</span>
                  @endforeach
                </p>
              </div>
            </div>
            {!! Form::close() !!}
            <table class="table tablesorter table-striped"
              id="">
              <thead class=" text-primary">
                <th scope="col">#</th>
                <th scope="col">Nome Completo</th>
                <th scope="col">Pedido/Itens</th>
                <th scope="col">Série/Turma</th>
                <th scope="col">Data</th>
                <th scope="col">Turno</th>
                <th scope="col"
                  class="text-right">Vl. Compra</th>
                <th scope="col"
                  class="text-right">Ação</th>
              </thead>
              <tbody>
                @forelse ($data as $item)
                  <tr>
                    <td>
                      <i class="fa fa-square"
                        aria-hidden="true"
                        style="color: {{ \App\Enums\OrderStatus::colors()[$item->status->value] }}"></i>
                    </td>

                    <td>{{ $item->dependent }}</td>
                    <td>
                      <b>
                        {{ $item->orderItems->implode(function ($element) {
                            return intval($element->quantity) . ' ' . $element->product->name;
                        }, '+ ') }}
                      </b>
                    </td>
                    <td>{{ $item->class }}</td>
                    <td>{{ brDate($item->date) }}</td>
                    <td>{{ !empty($item->turn) ? $item->turn->name() : 'Não informado' }}</td>
                    <td class="text-right">{{ floatToMoney($item->amount) }}</td>
                    <td class="text-right">
                      <button type="button"
                        data-item="{{ $item }}"
                        class="btn btn-sm btn-primary order-confirm">
                        @if ($item->status->isOpened())
                          Confirmar Entrega
                        @else
                          Entregue
                        @endif
                      </button>
                      {{-- <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item"
                                    href="{{ route('orders.confirm', $item) }}">Confirmar Entrega</a>
                            </div>
                        </div> --}}
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="20"
                      style="text-align: center; font-size: 1.1em;">
                      Nenhuma informação encontrada.
                    </td>
                  </tr>
                @endforelse

              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer py-4">
          <nav class="d-flex justify-content-end"
            aria-label="...">
            {{ $data->appends(request()->all())->links() }}
          </nav>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script>
    $(document).ready(function() {
      setTimeout('window.location.reload();', 50000);

      $('.order-confirm').on('click', function() {
        let item = $(this).data('item')
        $.get(`${getUrl()}/api/v1/order-confirm/${item.id}`)
          .done(function(response) {
            window.location.reload()
          })
          .fail(function(response) {
            $.notify(response.responseJSON.message, 'error')
          })
      })

    });
  </script>
@endpush
