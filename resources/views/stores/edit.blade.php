@extends('layouts.app', ['page' => 'Estabelecimentos', 'pageSlug' => 'stores'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h4 class="card-title">Estabelecimentos</h4>
              </div>
              <div class="ml-auto mr-3">
                <a href="{{ route('stores.index') }}"
                  class="btn btn-sm btn-primary">Voltar</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            {!! Form::open()->fill($item)->put()->route('stores.update', [$item->id])->multipart() !!}
            @include('stores._forms')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
