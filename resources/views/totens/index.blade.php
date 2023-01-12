@extends('layouts.app', ['page' => 'Totens', 'pageSlug' => 'totens'])

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card ">
        <div class="card-header">
          <div class="row">
            <div class="col-8">
              <h4 class="card-title">Totens</h4>
            </div>
            @can('totens_create')
              <div class="col-4 text-right">
                <a href="{{ route('totens.create') }}"
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
                <th scope="col">Nome</th>
                <th scope="col">Escola</th>
                <th scope="col">Token</th>
                <th scope="col">Dt. Criac.</th>
                <th scope="col">Dt. Atualiz.</th>
                <th scope="col">Status</th>
                <th scope="col"
                  class="text-right">Ação</th>
              </thead>
              <tbody>
                @forelse ($data as $item)
                  <tr style="font-size: 12px;">
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->store->people->full_name }}</td>
                    <td>{{ $item->token }}</td>
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
                          <form action="{{ route('totens.destroy', $item) }}"
                            method="post"
                            id="form-{{ $item->id }}">
                            @csrf
                            @method('delete')
                            @can('totens_view')
                              <a class="dropdown-item"
                                href="{{ route('totens.show', $item) }}">Visualizar</a>
                            @endcan
                            @can('totens_edit')
                              <a class="dropdown-item"
                                href="{{ route('totens.edit', $item) }}">Editar</a>
                            @endcan
                            @can('totens_delete')
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
