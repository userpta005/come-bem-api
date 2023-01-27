@extends('layouts.app', ['page' => 'PDV', 'pageSlug' => 'pdv'])

@push('css')
<style>
  .card {
    border-top-left-radius: 0px;
  }
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-12 d-flex justify-content-between align-items-center">
    @push('css')
    <style>
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
    <ul class="nav">
      <li class="nav-item nav-item-custom home">
        <a class="nav-link nav-link-custom"
          href="{{ route('home') }}">Painel Administrativo</a>
      </li>
      <li class="nav-item nav-item-custom pdv">
        <a class="nav-link nav-link-custom"
          href="{{ route('pdv') }}">PDV/Pedidos</a>
      </li>
    </ul>
    <span class="d-flex align-items-center"
      style="font-size:medium; color: black; font-weight: 600;">
      <span class="opened-cashier-span">
        {{ session('openedCashier') ? session('cashier')['description'] . ' aberto' : 'Caixa fechado' }}
      </span>
      <span class="opened-cashier-badge ml-2"
        style="background-color: {{ session('openedCashier') ? '#92e18b' : '#e18b8b' }}; height: 20px; width: 20px; border-radius: 50%;">
      </span>
    </span>
    @push('js')
    <script>
      const lastPart = window.location.href.split('/').pop().split('?')[0];
          const element = document.querySelector(`.${lastPart}`);
          element.classList.add("active-custom");
    </script>
    @endpush
  </div>
  <div class="col-12">
    <div class="card">
      @push('js')
      <script>
        window.tenant = {{ Illuminate\Support\Js::from(session('tenant')) }}
        window.store = {{ Illuminate\Support\Js::from(session('store')) }}
        window.currentUser = {{ Illuminate\Support\Js::from(auth()->user()) }}
      </script>
      @endpush
      <div id="app"
        class="card-body"></div>
      @vite('resources/js/pdv/app.js')
    </div>
  </div>
</div>
@endsection