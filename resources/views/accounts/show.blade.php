@extends('layouts.app', ['page' => 'Contas', 'pageSlug' => 'accounts'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-8">
                <h4 class="mb-card-title">Contas</h4>
              </div>
              <div class="col-4 text-right">
                <a href="{{ route('clients.index') }}"
                class="btn btn-sm btn-primary">Voltar</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="card-deck">
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Consumidor: </strong></p>
                  <p class="card-text">
                    {{ $item->dependent->info }}
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
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Escola: </strong></p>
                  <p class="card-text">
                    {{ $item->store->people->info }}
                  </p>
                </div>
              </div>
            </div>
            <div class="card-deck">
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Turno: </strong></p>
                  <p class="card-text">
                    {{ $item->turn->name() }}
                  </p>
                </div>
              </div>
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Saldo: </strong></p>
                  <p class="card-text">
                    {{ floatToMoney($item->balance) }}
                  </p>
                </div>
              </div>
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Limite Diário: </strong></p>
                  <p class="card-text">
                    {{ floatToMoney($item->daily_limit) }}
                  </p>
                </div>
              </div>
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Turma: </strong></p>
                  <p class="card-text">
                    {{ $item->class }}
                  </p>
                </div>
              </div>
              <div class="card m-2 shadow-sm">
                <div class="card-body">
                  <p><strong>Série: </strong></p>
                  <p class="card-text">
                    {{ $item->school_year }}
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
@endsection
