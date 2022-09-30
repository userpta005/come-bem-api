@extends('layouts.app', ['page' => 'Categorias Financeira', 'pageSlug' => 'financialcategories'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-8">
                <h4 class="card-title">Categorias Financeira</h4>
              </div>
              <div class="col-4 text-right">
                <a href="{{ route('financialcategories.index') }}"
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
                  <p><strong>Descrição: </strong></p>
                  <p class="card-text">
                    {{ $item->description }}
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
                  <p><strong>Tipo de Categoria: </strong></p>
                  <p class="card-text">
                    {{ $item->type->name() }}
                  </p>
                </div>
              </div>
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Classe: </strong></p>
                  <p class="card-text">
                    {{ $item->class->name() }}
                  </p>
                </div>
              </div>
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Antecessor: </strong></p>
                  <p class="card-text">
                    {{ count($item->ancestors) ? $item->ancestors[0]->info : 'Não Contém' }}
                  </p>
                </div>
              </div>
            </div>
            <div class="card-deck">
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Descritivo: </strong></p>
                  <p class="card-text">
                    {{ $item->descriptive ?? '' }}
                  </p>
                </div>
              </div>
            </div>
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
                  <p><strong>Dt. Atualização: </strong></p>
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
@endsection
