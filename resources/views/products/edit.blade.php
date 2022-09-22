@extends('layouts.app', ['page' => 'Produtos', 'pageSlug' => 'products'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-8">
                <h4 class="card-title">Produtos</h4>
              </div>
              <div class="col-4 text-right">
                <a href="{{ route('products.index') }}"
                  class="btn btn-sm btn-primary">Voltar</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            {!! Form::open()->fill($item)->put()->route('products.update', [$item->id])->multipart() !!}
            @include('products._forms')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
