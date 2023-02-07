<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Resumo Analitico do Caixa</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />

    <style>
        header {
            text-align: center;
            font-weight: bold;
        }

        header>.title {
            font-size: 1.875rem;
            margin: 0;
            padding: 10px;
            line-height: normal;
        }

        header>.local {
            margin: 0;
            padding: 10px;
        }

        header>.local>h2 {
            font-size: 30px;
            margin: 0;
            padding: 0;
        }

        header>.local>small {
            width: 100%;
            display: inline-block;
            font-size: 15px;
            font-weight: normal;
            line-height: normal;
        }

        header>.data {
            font-size: 20px;
            padding: 5px;
            margin: 0;
        }

        main>.data {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            padding: 5px;
            margin: 0;
        }

        .table td,
        .table thead th {
            vertical-align: middle;
            border-top: none;
            border-bottom: none;
        }

        .table td{
            padding-top: 0;
        }

        .withline{
            border-bottom: 1px solid #DEE2E6;
            border-top: 1px solid #DEE2E6;
        }


    </style>
</head>

<body onload="window.print()">

    <div>
        <header>
            <h1 class="title">
                <b>  Resumo do Caixa </b>
            </h1>

            @if(!isset($data[0]))
                <h1 class="title">
                    Dados não encontrados para esta busca.
                </h1>
            @else

            <h1 class="title" style="font-size: 1.4rem;">Analítico / Data: {{ carbon($data[0]->date_operation)->format('d/m/Y') }} </h1>
            
            @if ($data[0]->store)
            <div class="local " style="text-align: left">
                <h1 class="title" style="font-size: 1.4rem;">
                   Estabelecimento:  {{ $data[0]->store->info }} 
                </h1>
            </div>
            @endif

        </header>
        <main>
            @foreach ($data as $item)
            <table class="table" style="font-size: 0.9rem">
                <thead >
                    <tr style="font-size: 1rem" class="withline">
                        <th colspan="2">
                            Caixa: {{ $item->cashier->description }}
                        </th>
                        <th colspan="2">
                            Colaborador(a): {{  $item->user->people->full_name ? $item->user->people->full_name : $item->user->people->name  }}
                        </th>
                    </tr>
                    <tr>
                        <th>Dt. Mov.</th>
                        <th>E/S</th>
                        <th>Tipo Mov.</th>
                        <th>F.Pagto</th>
                        <th class="text-right">Entrada</th>
                        <th class="text-right">Saída</th>
                        <th class="text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item->cashMovements as $cashMovement)
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
                            {{ $cashMovement->movementType->class == \App\Enums\MovementClass::ENTRY ? money($cashMovement->amount)  : money(0) }}
                        </td>
                        <td class="text-right">
                            {{ $cashMovement->movementType->class == \App\Enums\MovementClass::OUTGOING ? money($cashMovement->amount)  : money(0) }}
                        </td>
                    </tr>
                    @endforeach
                    <tr style="border-top: 1px solid #DEE2E6;">
                    <td colspan="3">
                    </td>
                    <td>
                        <b>TOTAIS:</b>
                    </td>
                    <td class="text-right"> 
                        <b>{{ money($item->total_entries) }}</b>
                    </td>
                    <td class="text-right">
                        <b>{{ money($item->total_outgoing) }}</b>
                    </td>
                    <td class="text-right">
                        <b>{{ money($item->total_entries - $item->total_outgoing) }}</b>
                    </td>
                </tr>
                </tbody>
            </table>
            @endforeach
            @endif
        </main>
    </div>
</body>

</html>
