@extends('layouts.app', ['page' => 'Formas de Pagamento', 'pageSlug' => 'payment-methods'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-8">
                <h4 class="card-title">Formas de Pagamento</h4>
              </div>
              <div class="col-4 text-right">
                <a href="{{ route('payment-methods.index') }}"
                  class="btn btn-sm btn-primary">Voltar</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <div class="card-deck">
                  <div class="card m-2 shadow-sm">
                    <div class="card-body">
                      <p><strong>Imagem: </strong></p>
                      <img src="{{ asset($item->icon_url ?? 'images/noimage.png') }}"
                        style="max-width: 100%; max-height: 100%; border: 1px solid black;" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-9">
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
                      <p><strong>Nome: </strong></p>
                      <p class="card-text">
                        {{ $item->name }}
                      </p>
                    </div>
                  </div>
                  <div class="card m-2 shadow-sm">
                    <div class="card-body">
                      <p><strong>Status: </strong></p>
                      <p class="card-text">
                        {{ $item->status->name() }}
                      </p>
                    </div>
                  </div>
                </div>
              <div class="col-md-12">
                <div class="card-deck">
                  <div class="card m-2 shadow-sm">
                    <div class="card-body">
                      <p><strong>Dt. Criação: </strong></p>
                      <p class="card-text">
                        {{ $item->created_at->format('d/m/Y') }}
                      </p>
                    </div>
                  </div>
                  <div class="card m-2 shadow-sm">
                    <div class="card-body">
                      <p><strong>Dt. Atualização</strong></p>
                      <p class="card-text">
                        {{ $item->updated_at->format('d/m/Y') }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
