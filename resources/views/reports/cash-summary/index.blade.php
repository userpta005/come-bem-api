@extends('layouts.app', ['page' => 'Resumo do caixa', 'pageSlug' => 'reports.cash-summary'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header">
                <div class="row">
                    <div class="col-8">
                        <h4 class="card-title">Resumo do caixa</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open()->fill(request()->all())->get() !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! Form::select('cashier_id', 'Caixa:')
                        ->options($cashiers->prepend('Selecione...', ''), 'description')
                        ->attrs(['class' => 'select2'])
                        ->required()
                        !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::select('type', 'Formato:', [null => 'Selecione...'] + \App\Models\Cashier::types())
                        ->attrs(['class' => 'select2'])
                        ->required()
                        !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::date('date_operation', 'Dt. Movimento')->required() !!}
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
                            <div id="wrapper">
                                <div class="headReport" style="display:flex; align-items:end; justify-content:center;">
                                    <div style="width: 25%;">

                                    </div>
                                    <div style="width:50%">
                                        <h4 class="text-center" style="text-align: center;"> Resumo do Caixa </h4>
                                        <div class="text-center" style="width: 100%">
                                            @if(request()->type == 1)
                                            <input type="hidden" id="title-print" value="Resumo Sintetico">
                                            <div class="text-center" style="width: 100%; text-align: center;">
                                                <small style="color:grey;">Sintético / Data: {{ carbon($data[0]->date_operation)->format('d/m/Y') }}</small>
                                            </div>
                                            @else
                                            <input type="hidden" id="title-print" value="Resumo Analitico">
                                            <div class="text-center" style="width: 100%; text-align: center;">
                                                <small style="color:grey;">Analítico / Data: {{ carbon($data[0]->date_operation)->format('d/m/Y') }}</small>
                                            </div>
                                            @endif
                                        </div>
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
                                            @forelse ($data as $item)
                                            <tr class="table-light" style="border-top: 1px solid #DEE2E6;
                                            border-bottom: 1px solid #DEE2E6;">
                                                <th colspan="2">
                                                    {{ $data[0]->store->info }}
                                                </th>
                                                <th colspan="2">
                                                    Caixa: {{ $item->cashier->description }}
                                                </th>
                                                <th colspan="3" style="justify-content-end">
                                                    Colaborador(a): {{ $item->user->people->full_name ? $item->user->people->full_name : $item->user->people->name  }}
                                                </th>
                                            </tr>
                                            <tr style="margin-top: 1rem;">
                                                <th>Dt. Mov.</th>
                                                <th>E/S</th>
                                                <th>Tipo Mov.</th>
                                                <th>F.Pagto</th>
                                                <th class="text-right">Entrada</th>
                                                <th class="text-right">Saída</th>
                                                <th class="text-right"></th>
                                            </tr>
                                            @foreach ($item->cashMovements as $cashMovement)

                                            @if(request()->type == 1)
                                            <tr>
                                                <td>
                                                    {{ carbon(request()->date_operation)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    {{ $cashMovement->movementType->class->value }}
                                                </td>
                                                <td>
                                                    {{ $cashMovement->movementType->name }}
                                                </td>
                                                <td>
                                                    {{ optional($cashMovement->paymentMethod)->name ? $cashMovement->paymentMethod->name : 'Não informado'  }}
                                                </td>
                                                <td class="text-right">
                                                    {{ $cashMovement->movementType->class == \App\Enums\MovementClass::ENTRY ? money($cashMovement->amount)  : '0,00' }}
                                                </td>
                                                <td class="text-right">
                                                    {{ $cashMovement->movementType->class == \App\Enums\MovementClass::OUTGOING ? money($cashMovement->amount)  : '0,00' }}
                                                </td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td>
                                                    {{ carbon($cashMovement->date_operation)->format('d/m/Y H:i') }}
                                                </td>
                                                <td>
                                                    {{ $cashMovement->movementType->class->value }}
                                                </td>
                                                <td>
                                                    {{ $cashMovement->movementType->name }}
                                                </td>
                                                <td>
                                                    {{ optional($cashMovement->paymentMethod)->name ? $cashMovement->paymentMethod->name : 'Não informado'  }}
                                                </td>
                                                <td class="text-right">
                                                    {{ $cashMovement->movementType->class == \App\Enums\MovementClass::ENTRY ? money($cashMovement->amount)  : '0,00' }}
                                                </td>
                                                <td class="text-right">
                                                    {{ $cashMovement->movementType->class == \App\Enums\MovementClass::OUTGOING ? money($cashMovement->amount)  : '0,00' }}
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                            <tr style="border-top: 1px solid #DEE2E6;">
                                                <td colspan="3">
                                                </td>
                                                <td>
                                                    <b>TOTAIS:</b>
                                                </td>
                                                <td class="text-right">
                                                    <b>{{ $item->total_entries == 0 ? '0,00' : money($item->total_entries) }}</b>
                                                </td>
                                                <td class="text-right">
                                                    <b>{{ $item->total_outgoing == 0 ? '0,00': money($item->total_outgoing) }}</b>
                                                </td>
                                                <td class="text-right">
                                                    <b>{{ $item->total_entries - $item->total_outgoing == 0 ? '0,00' : money($item->total_entries - $item->total_outgoing) }}</b>
                                                </td>
                                            </tr>
                                            @empty
                                            <span style="text-align: center; font-size: 1.1em; display:block;">
                                                Nenhuma informação encontrada.
                                            </span>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                @if( isset(request()->type) )
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

@push('js')
<script>
    var title = "Resumo Analítico";

</script>

@endpush
