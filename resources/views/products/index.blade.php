@extends('layouts.app', ['page' => 'Produtos', 'pageSlug' => 'products'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Produtos</h4>
                        </div>
                        @can('products_create')
                            <div class="col-4 text-right">
                                <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">Adicionar Novo</a>
                            </div>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    @include('alerts.success')
                    @include('alerts.error')

                    {!! Form::open()->fill(request()->all())->get() !!}
                    <div class="row">
                        <div class="col-md-3">
                            {!! Form::text('search', 'Pesquisar por nome') !!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::select('section', 'Seção')->options($sections->prepend('Selecione...', ''))->attrs(['class' => 'select2']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::select('nutritional_classification', 'Class. Nutricional')->options($classifications->prepend('Selecione...', ''))->attrs(['class' => 'select2']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::select('status', 'Status')->options($status->prepend('Selecione...', ''))->attrs(['class' => 'select2']) !!}
                        </div>
                        <div class="col-md-12 text-right">
                            <br>
                            <button class="btn btn-sm  btn-primary" style="font-size: 9px;" type="submit"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="currentColor"
                                    class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
                                </svg> Filtrar</button>
                            <a id="clear-filter" style="font-size: 9px;" class="btn btn-sm btn-danger"
                                href="{{ route('products.index') }}"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="9" height="9" fill="currentColor" class="bi bi-trash-fill"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                </svg> Limpar</a>
                        </div>
                    </div>
                    {!! Form::close() !!}

                    <div class="row">
                        <div class="col-md-12 text-left">
                            <p style="font-size:15px;">
                                <b>Classificação Nutricional:</b>
                                <span style="margin-right: 10px;">
                                    <span class="dot" style="background-color:#808080;"></span>
                                    Em Análise
                                </span>
                                <span style="margin-right: 10px;">
                                    <span class="dot" style="background-color:#ff0000;"></span>
                                    Pouco Nutritivo
                                </span>
                                <span style="margin-right: 10px;">
                                    <span class="dot" style="background-color: #ffa500;"></span>
                                    Moderado
                                </span>
                                <span style="margin-right: 10px;">
                                    <span class="dot" style="background-color: #64dd17;"></span>
                                    Muito Nutritivo
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="">
                        <table class="table tablesorter table-striped">
                            <thead class="text-primary">
                                <th scope="col">#</th>
                                <th scope="col">Nome do Produto</th>
                                <th scope="col">Preço</th>
                                <th scope="col">P.Promocional</th>
                                <th scope="col">Seção</th>
                                <th scope="col">Class. Nutricional</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-right">Ação</th>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                    <tr style="font-size: 12px;">
                                        <td class="text-center">
                                            @if ($item->nutritional_classification == \App\Enums\NutritionalClassification::UNDER_ANALYSIS)
                                                <span class="dot" style="background-color: #808080;"></span>
                                            @elseif ($item->nutritional_classification == \App\Enums\NutritionalClassification::LITTLE_NUTRITIOUS)
                                                <span class="dot" style="background-color: #ff0000;"></span>
                                            @elseif ($item->nutritional_classification == \App\Enums\NutritionalClassification::MODERATE)
                                                <span class="dot" style="background-color: #ffa500;"></span>
                                            @elseif ($item->nutritional_classification == \App\Enums\NutritionalClassification::VERY_NUTRITIOUS)
                                                <span class="dot" style="background-color: #64dd17;"></span>
                                            @endif
                                        </td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{floatToMoney($item->price) }}</td>
                                        <td>{{ floatToMoney($item->promotion_price) }}</td>
                                        <td>{{ !empty($item->section) ? $item->section->name : 'Sem vinculo' }}</td>
                                        <td>{{ $item->nutritional_classification->name() }}</td>
                                        <td>{{ $item->status->name() }}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <form action="{{ route('products.destroy', $item->id) }}" method="post"
                                                        id="form-{{ $item->id }}">
                                                        @csrf
                                                        @method('delete')
                                                        @can('products_view')
                                                            <a class="dropdown-item"
                                                                href="{{ route('products.show', $item) }}">Visualizar</a>
                                                        @endcan
                                                        @can('products_edit')
                                                            <a class="dropdown-item"
                                                                href="{{ route('products.edit', $item) }}">Editar</a>
                                                        @endcan
                                                        @can('products_delete')
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
