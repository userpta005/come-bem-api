@extends('layouts.app', ['page' => 'Seções', 'pageSlug' => 'sections'])

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card ">
        <div class="card-header">
          <div class="row">
            <div class="col-md-8">
              <h4 class="card-title">Seções</h4>
            </div>
            @can('sections_create')
              <div class="col-md-4 text-right">
                <a href="{{ route('sections.create') }}"
                  class="btn btn-sm btn-primary">Adicionar Novo</a>
              </div>
            @endcan
          </div>
        </div>
        <div class="card-body">
          @include('alerts.success')
          @include('alerts.error')

          <div class="table-responsive-md">
            <table id="tree-table"
              class="table table-striped">
              <thead class=" text-primary">
                <th scope="col">Nome</th>
                <th scope="col">Descrição</th>
                <th scope="col">Tipo</th>
                <th scope="col">Dt. Criac.</th>
                <th scope="col">Dt. Atualiz.</th>
                <th scope="col">Status</th>
                <th scope="col"
                  class="text-right">Ação</th>
              </thead>
              <tbody>
                @forelse ($data as $item)
                  <tr style="font-size: 12px;"
                    data-id="{{ $item->id }}"
                    data-parent="{{ $item->parent_id ?? 0 }}"
                    data-level="{{ $item->depth }}">
                    <td data-column="name">{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->type->name() }}</td>
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
                          <form action="{{ route('sections.destroy', $item->id) }}"
                            method="post"
                            id="form-{{ $item->id }}">
                            @csrf
                            @method('delete')
                            @can('sections_view')
                              <a class="dropdown-item"
                                href="{{ route('sections.show', $item) }}">Visualizar</a>
                            @endcan
                            @can('sections_create')
                              @if ($item->status->isActive() && $item->depth < 2 && $item->type->isSynthetic())
                                <a class="dropdown-item"
                                  href="{{ route('sections.create', ['parent_id' => $item->getKey()]) }}">Cria
                                  descendente</a>
                              @endif
                            @endcan
                            @can('sections_edit')
                              <a class="dropdown-item"
                                href="{{ route('sections.edit', $item) }}">Editar</a>
                            @endcan
                            @can('sections_delete')
                              @if ($item->descendants_count == 0)
                                <button type="button"
                                  class="dropdown-item btn-delete">
                                  Excluir
                                </button>
                              @endif
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
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script src="{{ asset('js/tree.js') }}"></script>
@endpush
