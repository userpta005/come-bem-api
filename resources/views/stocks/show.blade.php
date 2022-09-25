@extends('layouts.app', ['page' => 'Saldo Estoque', 'pageSlug' => 'stocks'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h4 class="card-title">Saldo Estoque</h4>
              </div>
              <div class="col-md-4 text-right">
                <a href="{{ route('stocks.index') }}"
                  class="btn btn-sm btn-primary">Voltar</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="card-deck">
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Armazém: </strong></p>
                  <p class="card-text">
                    {{ $item->store->info }}
                  </p>
                </div>
              </div>
            </div>
            <div class="card-deck">
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Produto: </strong></p>
                  <p class="card-text">
                    {{ $item->info }}
                  </p>
                </div>
              </div>
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Quantidade Total: </strong></p>
                  <p class="card-text">
                    {{ floatToMoney($item->quantity) }}
                    {{ $item->product->um->initials }}
                  </p>
                </div>
              </div>
            </div>
            @if ($lots->isNotEmpty())
              <div class="card-deck">
                <div class="card m-2 shadow-sm">
                  <div class="card-header">
                    <div class="card-title"><strong>Lotes</strong></div>
                  </div>
                  <div class="card-body">
                    @foreach ($lots as $lot)
                      <div class="card-deck">
                        <div class="card m-2 shadow-sm">
                          <div class="card-body">
                            <p><strong>Lote Fornecedor: </strong></p>
                            <p class="card-text">
                              {{ $lot->provider_lot }}
                            </p>
                          </div>
                        </div>
                        <div class="card m-2 shadow-sm">
                          <div class="card-body">
                            <p><strong>Lote Interno: </strong></p>
                            <p class="card-text">
                              {{ $lot->lot }}
                            </p>
                          </div>
                        </div>
                        <div class="card m-2 shadow-sm">
                          <div class="card-body">
                            <p><strong>Quantidade: </strong></p>
                            <p class="card-text">
                              {{ floatToMoney($lot->quantity) }}
                            </p>
                          </div>
                        </div>
                        <div class="card m-2 shadow-sm">
                          <div class="card-body">
                            <p><strong>Data de Validade: </strong></p>
                            <p class="card-text">
                              {{ $lot->expiration_date ? $lot->expiration_date->format('d/m/Y') : null }}
                            </p>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
