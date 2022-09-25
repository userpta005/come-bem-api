@extends('layouts.app', ['page' => 'Requisição do Estoque', 'pageSlug' => 'stocks'])

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
            <div class="card-deck">
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Código: </strong></p>
                  <p class="card-text">
                    {{ $item->code }}
                  </p>
                </div>
              </div>
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Usuário: </strong></p>
                  <p class="card-text">
                    {{ $item->user->info }}
                  </p>
                </div>
              </div>
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Dt. Criação: </strong></p>
                  <p class="card-text">
                    {{ $item->created_at->format('d/m/Y') }}
                  </p>
                </div>
              </div>
            </div>
            <div class="card-deck">
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Tipo Movimentação: </strong></p>
                  <p class="card-text">
                    {{ $item->types($item->type) }}
                  </p>
                </div>
              </div>
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Armazém: </strong></p>
                  <p class="card-text">
                    {{ $item->stock->store->info }}
                  </p>
                </div>
              </div>
            </div>
            <div class="card-deck">
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Produto: </strong></p>
                  <p class="card-text">
                    {{ $item->stock->info }}
                  </p>
                </div>
              </div>
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Quantitdade: </strong></p>
                  <p class="card-text">
                    {{ floatToMoney($item->quantity) }}
                    {{ $item->stock->product->um->initials }}
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
