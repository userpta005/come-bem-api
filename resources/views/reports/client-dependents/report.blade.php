<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport"
    content="width=device-width">
  <meta charset="utf-8">
  <meta name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Relatório de Dependentes</title>
  <link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
    crossorigin="anonymous" />

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
        Relatório de Dependentes Ativos em {{ now()->format('d/m/Y') }}
      </h1>
      @if ($store instanceof \App\Models\Store)
        <div class="local">
          <h2>
            {{ $store->name }}
          </h2>
          <small>
            {{ $store->address . ', ' }}
            N° {{ ($store->number ?? 'S/N') . ', ' }}
            {{ !empty($store->district) ? $store->district . ', ' : '' }}
            {{ $store->city }}
          </small>
        </div>
      @endif
      <p class="data">Gerado em {{ now()->format('d/m/Y') }}</p>
    </header>
    <main>
      @foreach ($clients as $client)
        <table class="table table-bordered table-striped table-sm">
          <thead>
            <tr>
              <th colspan="4"
                class="table-secondary">
                Responsável: {{ $client->name }}
              </th>
            </tr>
            <tr>
              <th colspan="4"
                class="table-secondary">
                Data de Nascimento: {{ brDate($client->birthdate) }}
              </th>
            </tr>
            <tr>
              <th colspan="4"
                class="table-secondary">
                Número de Contato: {{ $client->phone }}
              </th>
            </tr>
            <tr>
              <th class="text-center">Nome</th>
              <th class="text-center">Saldo</th>
              <th class="text-center">Local</th>
              <th class="text-center">Data de Nascimento</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($client->dependents as $dependent)
              <tr>
                <td>
                  {{ $dependent->name }}
                </td>
                <td class="text-center">
                  {{ floatToMoney($dependent->accounts->sum('balance')) }}
                </td>
                <td class="text-center">
                  {{ $store->name }}
                </td>
                <td class="text-center">
                  {{ brDate($dependent->birthdate) }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endforeach
    </main>
  </div>
</body>

</html>
