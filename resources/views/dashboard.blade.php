@extends('layouts.app', ['page' => 'Dashboard', 'pageSlug' => 'dashboard'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Painel Administrativo</h4>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    @include('alerts.error')
                    <div class="">
                        {!! Form::open()->fill(request()->all())->get() !!}
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-12">
                                <p><b>Legenda:</b>
                                    @foreach ($status as $index => $element)
                                        <input type="checkbox" name="status[]" value="{{ $index }}"
                                            @if (in_array($index, $filterStatus)) checked @endif onChange="this.form.submit()">
                                        <i class="fa fa-square mr-1" aria-hidden="true"
                                            style="color: {{ \App\Enums\OrderStatus::colors()[$index] }}"></i>
                                        <span style="padding-right: 20px">{{ $element }}</span>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <table class="table tablesorter table-striped" id="">
                            <thead class=" text-primary">
                                <th scope="col">#</th>
                                <th scope="col">Nome Completo</th>
                                <th scope="col">Pedido/Itens</th>
                                <th scope="col">Série/Turma</th>
                                <th scope="col">Data/Hora</th>
                                <th scope="col" class="text-right">Vl. Compra</th>
                                <th scope="col" class="text-right">Ação</th>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                    <tr @if ($item->status->value == 1) style="background: @if ($item->created_at->addMinutes(5) > now()) #692e9a81 @else #f5365c93 @endif"
                                        @endif>
                                        <td>
                                            <i class="fa fa-square" aria-hidden="true"
                                                style="color: {{ \App\Enums\OrderStatus::colors()[$item->status->value] }}"></i>
                                        </td>

                                        <td>{{ $item->dependent }}</td>
                                        <td>
                                            {{ $item->orderItems->implode(function ($element) {
                                                return intval($element->quantity) . ' ' . $element->product->name;
                                            }, '+ ') }}
                                        </td>
                                        <td>{{ $item->class }}</td>
                                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-right">{{ floatToMoney($item->amount) }}</td>
                                        <td></td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item"
                                                        href="{{ route('orders.confirm', $item) }}">Confirmar Recarga</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="20" style="text-align: center; font-size: 1.1em;">
                                                Nenhuma informação encontrada.
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-end" aria-label="...">
                            {{ $data->appends(request()->all())->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('js')
        <script>
            $(document).ready(function() {
                setTimeout('window.location.reload();', 50000);
            });
        </script>
    @endpush
