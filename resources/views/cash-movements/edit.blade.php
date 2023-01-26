@extends('layouts.app', ['page' => 'Movimento do Caixa', 'pageSlug' => 'cash-movements'])

@section('content')
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="card-title">Movimento do Caixa</h4>
                        </div>
                        <div class="ml-auto mr-3">
                            <a href="{{ route('cash-movements.index') }}" class="btn btn-sm btn-primary">Voltar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open()->fill($item)->put()->route('cash-movements.update', [$item->id])->multipart() !!}
                    @include('cash-movements._forms')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection