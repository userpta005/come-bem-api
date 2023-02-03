@extends('layouts.app', ['page' => 'Extrato de Vendas', 'pageSlug' => 'reports.order-summary'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header">
                <div class="row">
                    <div class="col-12">
                        <h4 class="card-title">Extrato de Vendas</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open()->get()->attrs(['target' => '_blank'])->route('order.summary.report') !!}
                <div class="row">
                    <div class="col-md-6">
                        <x-select-ajax name="search" label="Consumidor:" route="/api/v1/dependents" prop="info" />
                    </div>
                    <div class="col-md-3">
                        {!! Form::date('date_start', 'Dt. Inicio')->required() !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::date('date_end', 'Dt. Fim')->required() !!}
                    </div>
                    <div class="col-md-12 d-flex justify-content-end align-items-center">
                        <button class="btn btn-primary" type="submit">Gerar Relatorio</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $('form').on('submit', function(event) {
        $(this).find('button').prop('disabled', false);
    });

</script>
@endpush
