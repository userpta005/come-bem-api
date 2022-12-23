@extends('layouts.app', ['page' => 'Cidades', 'pageSlug' => 'cities'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="card-title">Cidades</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    @include('alerts.error')

                    <div class="table-responsive">
                        <table class="table tablesorter table-striped" id="">
                            <caption>N. Registros: {{ $data->total() }}</caption>
                            <thead class=" text-primary">
                                <th scope="col">Nome</th>
                                <th scope="col">Estado</th>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                    <tr style="font-size: 12px;">
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->state->title }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="20" style="text-align: center; font-size: 1.1em;">
                                            Nenhuma informação cadastrada.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="overflow: auto" class="my-4 mx-3 row">
                    <nav class="d-flex ml-auto" aria-label="...">
                        {{ $data->appends(request()->all())->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
