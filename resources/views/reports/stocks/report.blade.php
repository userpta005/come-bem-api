<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport"
    content="width=device-width">
  <meta charset="utf-8">
  <meta name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
      border-bottom: 1px solid #dee2e6;
      margin: 0;
      padding: 10px;
      line-height: normal;
      font-size: 20px;
    }

    header .place>h2 {
      font-size: 16px;
    }

    header>.place {
      border-bottom: 1px solid #dee2e6;
      margin: 0;
      padding: 10px;
    }

    header .place h2 {
      font-size: 30px;
      margin: 0;
      padding: 0;
    }

    header>.place>small {
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

    .table-title {
      font-size: 20px;
    }

    .table td,
    .table thead th {
      vertical-align: middle;
    }

    .product-width {
      width: 40% !important;
    }

    .table td {
      width: 15%;
    }

    h3 {
      text-align: center;
    }
  </style>
</head>

<body>

  <div class="px-3">
    <header>
      <h1 class="title">
        Relatório do Estoque
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
      <p class="data">Gerado em {{ date('d/m/Y') }}</p>
    </header>
    <main>
      <div class="row">
        <div class="col-sm-12 mt-5">
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th class="w-75">Produto</th>
                <th class="text-right">Quantidade</th>
              </tr>
            </thead>
            <tbody>
              @forelse($stocks as $stock)
                <tr>
                  <td>{{ $stock->product->name }}</td>
                  <td class="text-right">{{ $stock->quantity }}</td>
                </tr>
              @empty
                <p>Não possui estoque.</p>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</body>
