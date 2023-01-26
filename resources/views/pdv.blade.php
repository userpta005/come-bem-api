@extends('layouts.app', ['page' => 'PDV', 'pageSlug' => 'pdv'])

@push('css')
  <style>
    .card {
      border-top-left-radius: 0px;
    }

    .nav-item-custom {
      background: rgba(0, 0, 0, 0.05);
      border-radius: 10px 10px 0 0;
      box-shadow: 0 1px 15px 0 rgba(0, 0, 0, 0.05);
    }

    .nav-link-custom {
      color: black;
      font-weight: 500;
    }

    .active-custom {
      background: white;
    }
  </style>
@endpush

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div>
        <ul class="nav">
          <li class="nav-item nav-item-custom home">
            <a class="nav-link nav-link-custom"
              href="{{ route('home') }}">Painel Administrativo</a>
          </li>
          <li class="nav-item nav-item-custom pdv">
            <a class="nav-link nav-link-custom"
              href="#">PDV/Pedidos</a>
          </li>
        </ul>
        <script>
          const currentUrl = window.location.href
          const lastPart = currentUrl.match(/\/([^\/]+)$/)[1]

          const home = document.querySelector('.home')
          const pdv = document.querySelector('.pdv')

          if (lastPart === 'home') {
            home.classList.add('active-custom')
          } else if (lastPart === 'pdv') {
            pdv.classList.add('active-custom')
          }
        </script>
      </div>
      <div class="card">
        <div class="card-body">
        </div>
      </div>
    </div>
  </div>
@endsection
