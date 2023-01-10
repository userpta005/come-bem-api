@extends('layouts.app', ['page' => 'Consumidors', 'pageSlug' => 'reports.client-dependents'])

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card ">
        <div class="card-header">
          <div class="row">
            <div class="col-12">
              <h4 class="card-title">Consumidors</h4>
            </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open()->get()->attrs(['target' => '_blank'])->route('client.dependents.report') !!}
          <div class="row">
            <div class="col-md-6">
              {!! Form::select('store_id', 'Loja')->options($stores->prepend('Selecione...', ''), 'info')->attrs(['class' => 'select2'])->required() !!}
            </div>
            <div class="col-md-12 d-flex justify-content-end align-items-center">
              <button class="btn btn-primary"
                type="submit">Gerar Relatorio</button>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
    <script>
      $('form').on('submit', function(event) {
        $(this).find('button').prop('disabled', false);
      });
    </script>
@endpush
