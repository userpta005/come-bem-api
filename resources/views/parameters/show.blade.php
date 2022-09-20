@extends('layouts.app', ['page' => 'Parâmetros', 'pageSlug' => 'parameters'])

@section('content')
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="card-title">Parâmetros</h4>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{ route('parameters.index') }}" class="btn btn-sm btn-primary">Voltar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-deck">
                        <div class="card m-2 shadow-sm">
                            <div class="card-body">
                                <p><strong>Nome: </strong></p>
                                <p class="card-text">
                                    {{ $item->name }}
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
                    </div>
                    <div class="card-deck">
                        <div class="card m-2 shadow-sm">
                            <div class="card-body">
                                <p><strong>Tipo: </strong></p>
                                <p class="card-text">
                                    {{ $item->type->name() }}
                                </p>
                            </div>
                        </div>
                        <div class="card m-2 shadow-sm">
                            <div class="card-body">
                                <p><strong>Valor: </strong></p>
                                <p class="card-text">
                                    {{ $item->value }}
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
            </div>
        </div>
    </div>
</div>
@endsection
