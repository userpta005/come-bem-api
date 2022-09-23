@extends('layouts.app', ['page' => 'Clientes', 'pageSlug' => 'clients'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h4 class="card-title">Clientes</h4>
              </div>
              <div class="ml-auto mr-3">
                <a href="{{ route('clients.index') }}"
                  class="btn btn-sm btn-primary">Voltar</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            {!! Form::open()->post()->route('clients.store')->multipart() !!}
            @include('clients._forms')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
