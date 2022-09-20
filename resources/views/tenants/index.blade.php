@extends('layouts.app', ['page' => 'Contratantes', 'pageSlug' => 'Contratantes'])

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Contratantes</h4>
            </div>
            <div class="col-md-6 text-right">
              <a href="{{ route('tenants.create') }}"
                class="btn btn-sm btn-primary">Adicionar Novo</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          @include('alerts.success')
          @include('alerts.error')
          {!! Form::open()->fill(request()->all())->get() !!}
          <div class="row">
            <div class="col-md-6">
              {!! Form::select('search', 'Nome Completo/Razão Social/CPF/CNPJ')->options($tenants->prepend('Selecione...'), 'info')->attrs(['class' => 'select2']) !!}
            </div>
            <div class="col-md-2">
              {!! Form::select('status', 'Status')->options(\App\Enums\TenantStatus::all()->prepend('Selecione...', ''))->attrs(['class' => 'select2']) !!}
            </div>
            <div class="col-md-2">
              {!! Form::select('signature', 'Assinatura')->options(\App\Enums\TenantSignature::all()->prepend('Selecione...', ''))->attrs(['class' => 'select2']) !!}
            </div>
            <div class="col-md-2">
              {!! Form::select('due_day', 'Dt. Vencimento')->options(\App\Enums\TenantDueDays::all()->prepend('Selecione...', ''))->attrs(['class' => 'select2']) !!}
            </div>
            <div class="col-md-3">
              {!! Form::date('dt_accession_start', 'Dt. Adesão Inicio') !!}
            </div>
            <div class="col-md-3">
              {!! Form::date('dt_accession_end', 'Dt. Adesão Fim') !!}
            </div>
            <div class="col-md-3">
              {!! Form::date('due_date_start', 'Dt. Vigência Inicio') !!}
            </div>
            <div class="col-md-3">
              {!! Form::date('due_date_end', 'Dt. Vigência Fim') !!}
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
                href="{{ route('tenants.index') }}">
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
          <div class="row">
            <div class="col-md-12 text-left">
              <p style="font-size:15px;"><b>Legenda:</b>
                <span style="margin-right: 10px;">
                  <span class="dot"
                    style="background-color:#008000;"></span>
                  Habilidade
                </span>
                <span style="margin-right: 10px;">
                  <span class="dot"
                    style="background-color:#ffa500;"></span>
                  Inadinplente
                </span>
                <span style="margin-right: 10px;">
                  <span class="dot"
                    style="background-color: #ff0000;"></span>
                  Suspenso
                </span>
                <span style="margin-right: 10px;">
                  <span class="dot"
                    style="background-color: #000000;"></span>
                  Encerrado
                </span>
              </p>
            </div>
          </div>
          <table class="table table-striped"
            id="">
            <thead class=" text-primary">
              <th scope="col">#</th>
              <th scope="col">Nome C./Razão S./CPF/CNPJ</th>
              <th scope="col">Email</th>
              <th scope="col">Ass.</th>
              <th scope="col">Dt.Venc.</th>
              <th scope="col">Dt.Adesão</th>
              <th scope="col">Dt.Vigência</th>
              <th scope="col">Ação</th>

            </thead>
            <tbody>
              @forelse ($data as $item)
                <tr>
                  <td class="text-center">
                    @if ($item->status->value == 1)
                      <span class="dot"
                        style="background-color: #008000;"></span>
                    @elseif ($item->status->value == 2)
                      <span class="dot"
                        style="background-color: #ffa500;"></span>
                    @elseif ($item->status->value == 3)
                      <span class="dot"
                        style="background-color: #ff0000;"></span>
                    @elseif ($item->status->value == 4)
                      <span class="dot"
                        style="background-color: #000000;"></span>
                    @endif
                  </td>
                  <td>{{ $item->info }}</td>
                  <td>{{ $item->email }}</td>
                  <td>{{ $item->signature->name() }}</td>
                  <td>{{ $item->due_day->name() }}</td>
                  <td>{{ $item->dt_accession->format('d/m/Y') }}</td>
                  <td>{{ $item->due_date->format('d/m/Y') }}</td>
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
                        <form action="{{ route('tenants.destroy', $item->id) }}"
                          method="post"
                          id="form-{{ $item->id }}">
                          @csrf
                          @method('delete')
                          @can('tenants_view')
                            <a class="dropdown-item"
                              href="{{ route('tenants.show', $item) }}">Visualizar</a>
                          @endcan
                          @can('tenants_edit')
                            <a class="dropdown-item"
                              href="{{ route('tenants.edit', $item) }}">Editar</a>
                          @endcan
                          @can('tenants_delete')
                            <button type="button"
                              class="dropdown-item btn-delete">
                              Excluir
                            </button>
                          @endcan
                        </form>
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
