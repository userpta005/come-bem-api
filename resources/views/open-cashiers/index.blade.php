@extends('layouts.app', ['page' => 'Abertura/Fechamento', 'pageSlug' => 'open-cashiers'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="card-title">Abertura/Fechamento</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @include('alerts.success')
                @include('alerts.error')
                {!! Form::open()->fill(request()->all())->get() !!}
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::select('cashier_id', 'Caixa')
                        ->options($cashiers->prepend('Selecione...', ''), 'description')
                        ->attrs(['class' => 'select2'])
                        !!}
                    </div>
                    <div class="col-md-4">
                        {!!Form::select('user_id', 'Usuário:', $users->prepend('Selecione...', ''))
                        ->attrs(['class' => 'select2'])
                        !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::date('start_date', 'Dt. Inicio') !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::date('end_date', 'Dt. Fim') !!}
                    </div>

                    <div class="col-md-12 d-flex justify-content-end align-items-center">
                        <button class="btn btn-sm btn-primary mr-1" style="font-size: 9px;" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
                            </svg>
                            Filtrar
                        </button>
                        <a id="clear-filter" style="font-size: 9px;" class="btn btn-sm btn-danger" href="{{ route('open-cashiers.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                            </svg>
                            Limpar
                        </a>
                    </div>
                </div>
                {!! Form::close() !!}
                <div class="table-responsive-md">
                    <table class="table tablesorter table-striped">
                        <caption>N. Registros: {{ $data->total() }}</caption>
                        <thead class=" text-primary">
                            <th scope="col">Caixa</th>
                            <th scope="col">Usuário</th>
                            <th scope="col">Operação</th>
                            <th scope="col">Saldo</th>
                            <th scope="col">Dt. Operação/Hora</th>
                            <th scope="col" class="text-right">Ação</th>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                            <tr style="font-size: 12px;">
                                <td class="max-column">{{ $item->cashier->description }}</td>
                                <td class="max-column">{{ $item->user->people->info }}</td>
                                <td class="max-column">{{ $item->operations($item->operation) }}</td>
                                <td class="max-column"> {{ money($item->balance) }}</td>
                                <td class="max-column">{{ carbon($item->date_operation)->format('d/m/Y H:i') }}</td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <form action="{{ route('open-cashiers.destroy', $item->id) }}" method="post" id="form-{{ $item->id }}">
                                                @csrf
                                                @method('delete')
                                                @can('open-cashiers_view')
                                                <a class="dropdown-item" href="{{ route('open-cashiers.show', $item) }}">Visualizar</a>
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
            <div style="overflow: auto" class="my-4 mx-3 row">
                <nav class="d-flex ml-auto" aria-label="...">
                    {{ $data->appends(request()->all())->links() }}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
