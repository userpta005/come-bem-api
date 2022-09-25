@extends('layouts.app', ['page' => 'Formas de Pagamento', 'pageSlug' => 'payment-methods'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-8">
                <h4 class="card-title">Formas de Pagamento</h4>
              </div>
              <div class="col-4 text-right">
                <a href="{{ route('payment-methods.index') }}"
                  class="btn btn-sm btn-primary">Voltar</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            {!! Form::open()->post()->route('payment-methods.store')->multipart() !!}
            @include('payment-methods._forms')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
