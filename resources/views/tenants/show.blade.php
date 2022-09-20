@extends('layouts.app', ['page' => 'Contratantes', 'pageSlug' => 'tenants'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h4 class="card-title">Contratantes</h4>
              </div>
              <div class="ml-auto mr-3">
                <a href="{{ route('tenants.index') }}"
                  class="btn btn-sm btn-primary">Voltar</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="painel-header">
              <div class="card-deck">
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>CPF/CNPJ: </strong></p>
                    <p class="card-text">
                      {{ $item->nif }}
                    </p>
                  </div>
                </div>
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Nome Completo/Razão Social: </strong></p>
                    <p class="card-text">
                      {{ $item->name }}
                    </p>
                  </div>
                </div>
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Nome Social/Nome Fantasia: </strong></p>
                    <p class="card-text">
                      {{ $item->full_name }}
                    </p>
                  </div>
                </div>
              </div>
              <div class="card-deck">
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Inscrição Estadual: </strong></p>
                    <p class="card-text">
                      {{ $item->state_registration }}
                    </p>
                  </div>
                </div>
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Inscrição Municipal: </strong></p>
                    <p class="card-text">
                      {{ $item->city_registration }}
                    </p>
                  </div>
                </div>
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>DT. Nasc/Abertura: </strong></p>
                    <p class="card-text">
                      {{ brDate($item->birthdate) }}
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
                    <p><strong>Email: </strong></p>
                    <p class="card-text">
                      {{ $item->email }}
                    </p>
                  </div>
                </div>
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Telefone: </strong></p>
                    <p class="card-text">
                      {{ $item->phone }}
                    </p>
                  </div>
                </div>
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Cidade: </strong></p>
                    <p class="card-text">
                      {{ $item->city }}
                    </p>
                  </div>
                </div>
              </div>
              <div class="card-deck">
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>CEP: </strong></p>
                    <p class="card-text">
                      {{ $item->zip_code }}
                    </p>
                  </div>
                </div>
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Endereço: </strong></p>
                    <p class="card-text">
                      {{ $item->address }}
                    </p>
                  </div>
                </div>
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Bairro: </strong></p>
                    <p class="card-text">
                      {{ $item->district }}
                    </p>
                  </div>
                </div>
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Número: </strong></p>
                    <p class="card-text">
                      {{ $item->number }}
                    </p>
                  </div>
                </div>
              </div>
              <div class="card-deck">
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Celular: </strong></p>
                    <p class="card-text">
                      {{ $item->cellphone }}
                    </p>
                  </div>
                </div>
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Contato: </strong></p>
                    <p class="card-text">
                      {{ $item->contact }}
                    </p>
                  </div>
                </div>
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Contato Telefone: </strong></p>
                    <p class="card-text">
                      {{ $item->contact_phone }}
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
                    <p><strong>Dt. Edição: </strong></p>
                    <p class="card-text">
                      {{ $item->updated_at->format('d/m/Y') }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="painel-body">
              <div class="card-deck">
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Assinatura: </strong></p>
                    <p class="card-text">
                      {{ $item->signature->name() }}
                    </p>
                  </div>
                </div>
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Dt. Adesão: </strong></p>
                    <p class="card-text">
                      {{ $item->dt_accession->format('d/m/Y') }}
                    </p>
                  </div>
                </div>
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Dt. Vigência Assinatura: </strong></p>
                    <p class="card-text">
                      {{ $item->due_date->format('d/m/Y') }}
                    </p>
                  </div>
                </div>
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Dia do vencimento: </strong></p>
                    <p class="card-text">
                      {{ $item->due_day->name() }}
                    </p>
                  </div>
                </div>
              </div>
              <div class="card-deck">
                <div class="card m-2 shadow-sm">
                  <div class="card-body">
                    <p><strong>Valor: </strong></p>
                    <p class="card-text">
                      {{ floatToMoney($item->value) }}
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
@endsection
