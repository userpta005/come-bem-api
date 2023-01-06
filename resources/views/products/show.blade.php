@extends('layouts.app', ['page' => 'Produtos', 'pageSlug' => 'products'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-8">
                <h4 class="card-title">Produtos</h4>
              </div>
              <div class="col-4 text-right">
                <a href="{{ route('products.index') }}"
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
                      <img src="{{ asset($item->image_url ?? 'images/noimage.png') }}"
                        style="max-width: 100%; max-height: 100%; border: 1px solid black;" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-9">
                <div class="card-deck">
                  <div class="card m-2 shadow-sm">
                    <div class="card-body">
                      <p><strong>Nome do Produto: </strong></p>
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
                <div class="card-deck">
                  <div class="card m-2 shadow-sm">
                    <div class="card-body">
                      <p><strong>Class. Nutricional: </strong></p>
                      <p class="card-text">
                        {{ $item->nutritional_classification->name() }}
                      </p>
                    </div>
                  </div>
                  <div class="card m-2 shadow-sm">
                    <div class="card-body">
                      <p><strong>Un.Medida: </strong></p>
                      <p class="card-text">
                        {{ $item->um->name }}
                      </p>
                    </div>
                  </div>
                  <div class="card m-2 shadow-sm">
                    <div class="card-body">
                      <p><strong>NCM: </strong></p>
                      <p class="card-text">
                        {{ $item->ncm->description }}
                      </p>
                    </div>
                  </div>
                </div>

                <div class="card-deck">
                  <div class="card m-2 shadow-sm">
                    <div class="card-body">
                      <p><strong>Preço: </strong></p>
                      <p class="card-text">
                        {{ floatToMoney($item->price) }}
                      </p>
                    </div>
                  </div>
                  <div class="card m-2 shadow-sm">
                    <div class="card-body">
                      <p><strong>Preço Promocional: </strong></p>
                      <p class="card-text">
                        {{ floatToMoney($item->promotion_price) }}
                      </p>
                    </div>
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
