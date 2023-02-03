<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Extrato de Vendas</title>
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

        .table td {
            padding-top: 0;
        }

        .withline {
            border-bottom: 1px solid #DEE2E6;
            border-top: 1px solid #DEE2E6;
        }

    </style>
</head>

<body onload="window.print()">

    <div>
        <header>
            <h1 class="title">
                <b>Extrato de Vendas</b>
            </h1>

            @if(!isset($data[0]))
            <h1 class="title">
                Dados não encontrados para esta busca.
            </h1>
            @else

            <h1 class="title" style="font-size: 1.4rem;">Data: {{ carbon($data[0]->date_operation)->format('d/m/Y') }} </h1>

        </header>
        <main>
            <table class="table" style="font-size: 0.9rem">
                <thead>
                    <tr style="font-size: 1rem;" class="withline">
                        <th colspan="2">
                            Contratante: {{ optional($data[0]->account->store->tenant->people)->name }}
                        </th>
                        <th colspan="2">
                            Estabelecimento: {{ optional($data[0]->account->store->people)->name }}
                        </th>
                    </tr>
                    <tr>
                        <th>Dt. Venda.</th>
                        <th>Pedido</th>
                        <th>Consumidor</th>
                        <th class="text-right">Qtde.</th>
                        <th class="text-right">Vl Venda</th>
                        <th class="text-right">F.Pagto</th>
                    </tr>
                </thead>
                <tbody>
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
            @endif
        </main>
    </div>
</body>

</html>
