@extends('layouts.app', ['page' => 'Saldo Inicial', 'pageSlug' => 'openingbalances'])

@section('content')
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h4 class="card-title">Saldo Inicial</h4>
              </div>
              <div class="col-md-4 text-right">
                <a href="{{ route('openingbalances.index') }}"
                  class="btn btn-sm btn-primary">Voltar</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            {!! Form::open()->post()->route('openingbalances.store')->multipart() !!}
              @include('openingbalances._forms')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script>
    $('#product_id').on('change', function() {
      $input_lot = $('#inp-provider_lot');
      $input_shelf_life = $('#inp-expiration_date');

      var has_lot = Number($(this).find('option:selected').data('lot'));

      $input_lot.prop('required', has_lot)
      $input_lot.prop('readonly', !has_lot)

      $input_shelf_life.prop('required', has_lot)
      $input_shelf_life.prop('readonly', !has_lot)


      $input_um = $('#um');

      $input_um.val($(this).find('option:selected').data('um'));
    });
  </script>
@endpush
