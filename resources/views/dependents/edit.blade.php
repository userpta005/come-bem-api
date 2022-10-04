@extends('layouts.app', ['page' => 'Consumidores', 'pageSlug' => 'dependents'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h4 class="card-title">Consumidores</h4>
              </div>
              <div class="ml-auto mr-3">
                <a href="{{ route('clients.dependents.index', ['client' => $client]) }}"
                  class="btn btn-sm btn-primary">Voltar</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            {!! Form::open()->fill($item)->put()->route('clients.dependents.update', ['client' => $client, $item->id])->multipart() !!}
            @include('dependents._forms')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
