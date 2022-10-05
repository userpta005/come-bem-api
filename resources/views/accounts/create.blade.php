@extends('layouts.app', ['page' => 'Contas', 'pageSlug' => 'account'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="account">
          <div class="account-header">
            <div class="row align-items-center">
              <div class="col-8">
                <h4 class="account-title">Contas</h4>
              </div>
              <div class="col-4 text-right">
                <a href="{{ route('dependents.accounts.index', ['dependent' => $dependent]) }}"
                  class="btn btn-sm btn-primary">Voltar</a>
              </div>
            </div>
          </div>
          <div class="account-body">
            {!! Form::open()->post()->route('dependents.accounts.store', ['dependent' => $dependent])->multipart() !!}
            @include('accounts._forms')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
