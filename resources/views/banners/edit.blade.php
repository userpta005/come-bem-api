@extends('layouts.app', ['page' => 'Banners', 'pageSlug' => 'banners'])

@section('content')
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="card-title">Mídias</h4>
                        </div>
                        <div class="col-4 text-right">
                            <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal"
                                data-target="#midias">
                                Tamanho das Mídias
                            </button>
                            <a href="{{ route('banners.index') }}" class="btn btn-sm btn-primary">Voltar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!!Form::open()->fill($item)
                    ->put()
                    ->route('banners.update', [$item->id])
                    ->multipart()
                    !!}
                        @include('banners._forms')
                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
