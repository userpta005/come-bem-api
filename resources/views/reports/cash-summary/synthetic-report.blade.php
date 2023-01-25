<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Resumo Sintetico do Caixa</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />

    <style>
        header {
            font-family: 'Roboto', serif;
            border: 1px solid #dee2e6;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        header>.title {
            font-size: 20px;
            border-bottom: 1px solid #dee2e6;
            margin: 0;
            padding: 10px;
            line-height: normal;
        }

        header>.local {
            border-bottom: 1px solid #dee2e6;
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
            border: 1px solid #dee2e6;
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            padding: 5px;
            margin: 0;
        }

        .table td,
        .table thead th {
            vertical-align: middle;
        }

    </style>
</head>

<body onload="window.print()">

    <div>
        <header> 
            <h1 class="title">
                Resumo Sintético do Caixa
            </h1>
            @if(!isset($data[0]))
            <h1 class="title">
                Dados não encontrados para esta busca.
            </h1>
            @else
            @if ($data[0]->store)
            <div class="local">
                <h2>
                    {{ $data[0]->store->info }}
                </h2>
                <small>
                    {{ $data[0]->store->people->address . ', ' }}
                    N° {{ ($data[0]->store->people->number ?? 'S/N') . ', ' }}
                    {{ !empty($data[0]->store->people->district) ? $data[0]->store->people->district . ', ' : '' }}
                    {{ $data[0]->store->people->city->title }}
                </small>
            </div>
            @endif

            <p class="data">Sintético / Data: {{ carbon($data[0]->date_operation)->format('d/m/Y') }}</p>
        </header>
        <main>
            @foreach ($data as $item)
            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th colspan="4" class="table-secondary">
                            Caixa: {{ $item->cashier->description }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="4" class="table-secondary">
                            Colaborador(a): {{ $item->user->people->info }}
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center">Dt. Mov.</th>
                        <th class="text-center">E/S</th>
                        <th class="text-center">Tipo Mov.</th>
                        <th class="text-center">F.Pagto</th>
                        <th class="text-center">Entrada</th>
                        <th class="text-center">Saída</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($item->cashMovements as $cashMovement)
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
                            {{ $cashMovement->paymentMethod->name }}
                        </td>
                        <td class="text-center">
                            {{ $cashMovement->movementType->class == \App\Enums\MovementClass::ENTRY ? money($cashMovement->amount)  : money(0) }}
                        </td>
                        <td class="text-center">
                            {{ $cashMovement->movementType->class == \App\Enums\MovementClass::OUTGOING ? money($cashMovement->amount)  : money(0) }}
                        </td>
                    </tr>
                    @endforeach

                    <td class="text-center">
                    </td>
                    <td class="text-center">
                    </td>
                    <td class="text-center">
                    </td>
                    <td class="text-center">
                        <b>TOTAIS:</b>
                    </td>
                    <td class="text-center"> 
                        <b>{{ money($item->total_entries) }}</b>
                    </td>
                    <td class="text-center">
                        <b>{{ money($item->total_outgoing) }}</b>
                    </td>
                    <td class="text-center">
                        <b>{{ money($item->total_entries - $item->total_outgoing) }}</b>
                    </td>
                </tbody>
            </table>
            @endforeach
            @endif
        </main>
    </div>
</body>

</html>
