@extends('layouts.app', ['page' => 'Saldo Inicial', 'pageSlug' => 'openingbalances'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h4 class="card-title">Saldo Inicial</h4>
              </div>
              <div class="ml-auto mr-3">
                <a href="{{ route('openingbalances.index') }}"
                  class="btn btn-sm btn-primary">Voltar</a>
              </div>
            </div>
          </div>
          <div class="card-body">
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
                  <p><strong>Un. Medida: </strong></p>
                  <p class="card-text">
                    {{ $item->product->um->name }}
                  </p>
                </div>
              </div>
            </div>
            <div class="card-deck">
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Armazém: </strong></p>
                  <p class="card-text">
                    {{ $item->store->info }}
                  </p>
                </div>
              </div>
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Data: </strong></p>
                  <p class="card-text">
                    {{ brDate($item->date) }}
                  </p>
                </div>
              </div>
            </div>
            <div class="card-deck">
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Quantidade: </strong></p>
                  <p class="card-text">
                    {{ floatToMoney($item->quantity) }}
                    {{ $item->product->um->initials }}

                  </p>
                </div>
              </div>
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Lote Interno: </strong></p>
                  <p class="card-text">
                    {{ $item->lot }}
                  </p>
                </div>
              </div>
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Lote Fornecedor: </strong></p>
                  <p class="card-text">
                    {{ $item->provider_lot }}
                  </p>
                </div>
              </div>
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Data de Validade: </strong></p>
                  <p class="card-text">
                    {{ isset($item) && $item->expiration_date ? $item->expiration_date->format('d/m/Y') : null }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
