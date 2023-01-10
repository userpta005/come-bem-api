@extends('layouts.app', ['page' => 'Contas', 'pageSlug' => 'accounts'])

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card ">
        <div class="card-header">
          <div class="row">
            <div class="col-8">
              <h4 class="card-title">Contas</h4>
            </div>
            @can('accounts_create')
              <div class="col-4 text-right">
                <a href="{{ route('clients.index') }}"
                class="btn btn-sm btn-primary">Voltar</a>
                <a href="{{ route('dependents.accounts.create', ['dependent' => $dependent]) }}"
                  class="btn btn-sm btn-primary">Adicionar Novo</a>
              </div>
            @endcan
          </div>
        </div>
        <div class="card-body">
          @include('alerts.success')
          @include('alerts.error')

          <div class="table-responsive-md">
            <table class="table tablesorter table-striped">
              <thead class=" text-primary">
                <th scope="col">Consumidor</th>
                <th scope="col">Saldo</th>
                <th scope="col">Limite Diário</th>
                <th scope="col">Turma</th>
                <th scope="col">Série</th>
                <th scope="col">Dt. Criac.</th>
                <th scope="col">Dt. Atualiz.</th>
                <th scope="col">Status</th>
                <th scope="col"
                  class="text-right">Ação</th>
              </thead>
              <tbody>
                @forelse ($data as $item)
                  <tr style="font-size: 12px;">
                    <td>{{ $item->dependent->info }}</td>
                    <td>{{ floatToMoney($item->balance) }}</td>
                    <td>{{ floatToMoney($item->daily_limit) }}</td>
                    <td>{{ $item->class }}</td>
                    <td>{{ $item->school_year }}</td>
                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                    <td>{{ $item->updated_at->format('d/m/Y') }}</td>
                    <td>{{ $item->status->name() }}</td>
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
                          <form action="{{ route('dependents.accounts.destroy', ['dependent' => $dependent, $item]) }}"
                            method="post"
                            id="form-{{ $item->id }}">
                            @csrf
                            @method('delete')
                            @can('cards_view')
                              <a class="dropdown-item"
                                href="{{ route('accounts.cards.index', ['account' => $item]) }}">Cartões</a>
                            @endcan
                            @can('accounts_view')
                              <a class="dropdown-item"
                                href="{{ route('dependents.accounts.show', ['dependent' => $dependent, $item]) }}">Visualizar</a>
                            @endcan
                            @can('accounts_edit')
                              <a class="dropdown-item"
                                href="{{ route('dependents.accounts.edit', ['dependent' => $dependent, $item]) }}">Editar</a>
                            @endcan
                            @can('accounts_delete')
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
        </div>
        <div class="card-footer py-4">
          <nav class="d-flex justify-content-end"
            aria-label="...">
            {{ $data->links() }}
          </nav>
        </div>
      </div>
    </div>
  </div>
@endsection
