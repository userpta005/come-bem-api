@extends('layouts.app', ['page' => 'Contas', 'pageSlug' => 'account'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-8">
                <h4 class="card-title">Contas</h4>
              </div>
              <div class="col-4 text-right">
                <a href="{{ route('dependents.accounts.index', ['dependent' => $dependent]) }}"
                  class="btn btn-sm btn-primary">Voltar</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            {!! Form::open()->fill($item)->put()->route('dependents.accounts.update', ['dependent' => $dependent, $item->id])->multipart() !!}
            @include('accounts._forms')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
