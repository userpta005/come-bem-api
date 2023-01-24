@extends('layouts.app', ['page' => 'Tipo de Movimento', 'pageSlug' => 'movement-types'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header">
                <div class="row">
                    <div class="col-8">
                        <h4 class="card-title">Tipo de Movimento</h4>
                    </div>
                    @can('movement-types_create')
                    <div class="col-4 text-right">
                        <a href="{{ route('movement-types.create') }}" class="btn btn-sm btn-primary">Adicionar Novo</a>
                    </div>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                @include('alerts.success')
                @include('alerts.error')

                <div class="table-responsive-md">
                    <table class="table tablesorter table-striped">
                      <caption>N. Registros: {{ $data->total() }}</caption>
                        <thead class=" text-primary">
                            <th scope="col">Tipo</th>
                            <th scope="col">Classe</th>
                            <th scope="col">Dt. Criac.</th>
                            <th scope="col">Dt. Atualiz.</th>
                            <th scope="col" class="text-right">Ação</th>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                            <tr style="font-size: 12px;">
                                <td class="max-column">{{ $item->type }}</td>
                                <td>{{ $item->classOption($item->class) }}</td>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>{{ $item->updated_at->format('d/m/Y') }}</td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <form action="{{ route('movement-types.destroy', $item->id) }}" method="post" id="form-{{ $item->id }}">
                                                @csrf
                                                @method('delete')
                                                @can('movement-types_view')
                                                <a class="dropdown-item" href="{{ route('movement-types.show', $item) }}">Visualizar</a>
                                                @endcan
                                                @can('movement-types_edit')
                                                <a class="dropdown-item" href="{{ route('movement-types.edit', $item) }}">Editar</a>
                                                @endcan
                                                @can('movement-types_delete')
                                                <button type="button" class="dropdown-item btn-delete">
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
                                <td colspan="20" style="text-align: center; font-size: 1.1em;">
                                    Nenhuma informação cadastrada.
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer py-4">
                <nav class="d-flex justify-content-end" aria-label="...">
                    {{ $data->links() }}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
