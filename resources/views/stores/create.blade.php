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
            {!! Form::open()->post()->route('stores.store')->multipart() !!}
            @include('stores._forms')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script>
    swal({
      title: "Produtos !",
      text: "Para agilizar o cadastro de produtos das lojas/cantinas, confirme a replicação dos produtos?",
      icon: "warning",
      buttons: true,
      buttons: ["Cancelar", "Confirmar"]
    }).then((isConfirm) => {
      if (isConfirm) {
        const replicateProducts = document.querySelector('input[name=replicate_products]')
        replicateProducts.value = true
      }
    });
  </script>
@endpush
