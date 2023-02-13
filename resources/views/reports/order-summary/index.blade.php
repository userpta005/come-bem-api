@extends('layouts.app', ['page' => 'Extrato de Vendas', 'pageSlug' => 'reports.order-summary'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header">
                <div class="row">
                    <div class="col-8">
                        <h4 class="card-title">Extrato de Vendas</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open()->fill(request()->all())->get() !!}
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
                @if (isset($data) && !empty($data[0]))
                <div class="row" style="margin: 0 0.5rem">
                    <div class="col-12">
                        <div class="content section-top print">
                            <div class="row text-right no-print">
                                <div class="col-md-12" style="margin-top: 1rem;">
                                    <button class="btn btn-info btn-sm float-right btn-print"><i class="fas fa-print"></i></button>
                                </div>
                            </div>
                            <input type="hidden" id="title-print" value="Extrato de Vendas">
                            <div id="wrapper">
                                <div class="headReport" style="display:flex; align-items:end; justify-content:center;">
                                    <div style="width: 25%;">

                                    </div>
                                    <div style="width:50%">
                                        <h4 class="text-center" style="text-align: center;"> Extrato de Vendas </h4>
                                    </div>
                                    <div class="float-right text-right" style="width: 25%; margin-right:4%;">
                                        <small style="color:grey; width: 100%;">
                                            Emissão: {{ date('d/m/Y - H:i') }}
                                        </small>
                                    </div>
                                </div>
                                <br>
                                <div style="margin-left:3%; margin-right:3%;">
                                    <table class="table table-sm" style="font-size: 12px;">
                                        <tbody>
                                            <tr class="table-light" style="border-top: 1px solid #DEE2E6;
                                            border-bottom: 1px solid #DEE2E6;">
                                                <th colspan="2">
                                                    {{ optional($data[0]->account->store->people)->name }}
                                                </th>
                                                <th colspan="4">
                                                    Contratante: {{ optional($data[0]->account->store->tenant->people)->name }}
                                                </th>
                                            </tr>
                                            <tr style="margin-top: 1rem;">
                                                <th>Dt. Venda.</th>
                                                <th>Pedido</th>
                                                <th>Consumidor</th>
                                                <th class="text-right">Qtde.</th>
                                                <th class="text-right">Vl Venda</th>
                                                <th class="text-right">F.Pagto</th>
                                            </tr>
                                            @foreach ($data as $item)
                                            <tr>
                                                <td>
                                                    {{ carbon($item->date)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    {{ $item->id }}
                                                </td>
                                                <td>
                                                    {{ $item->dependent}}
                                                </td>
                                                <td class="text-right">
                                                     {{ floatToMoney( $item->total_quantity ) }}
                                                </td>
                                                <td class="text-right">
                                                    {{ floatToMoney($item->amount) }}
                                                </td>
                                                <td class="text-right">
                                                    {{ optional($item->paymentMethod)->name ? $item->paymentMethod->name : 'Não informado'  }}
                                                </td>
                                            </tr>
                                            @endforeach
                                            <tr style="border-top: 1px solid #DEE2E6;">
                                                <td colspan="2">
                                                </td>
                                                <td class="text-right">
                                                    <b>Totais:</b>
                                                </td>
                                                <td class="text-right">
                                                    <b> {{ floatToMoney( $data->sum('total_quantity') ) }}</b>
                                                </td>
                                                <td class="text-right">
                                                    <b> {{ floatToMoney( $data->sum('amount') ) }} </b>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    @if(  isset(request()->date_start) )
                    <span style="text-align: center; font-size: 1.1em; display:block;">
                        Nenhuma informação encontrada.
                    </span>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection