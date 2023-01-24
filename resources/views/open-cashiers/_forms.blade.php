<div class="row">
    <div class="col-md-4">
        {!! Form::select('cashier_id', 'Caixa')
        ->options($cashiers->prepend('Selecione...', ''), 'description')
        ->attrs(['class' => 'select2'])
        ->required()
        !!}
    </div>
    <div class="col-md-8">
        {!!Form::select('user_id', 'Usuário:')
        ->options($users->prepend('Selecione...', ''))
        ->attrs(['class' => 'select2'])
        ->required()
        !!}
    </div>
    <div class="col-md-4">
        {!! Form::select('operation', 'Operação:', [null => 'Selecione...'] + \App\Models\OpenCashier::operations())
        ->attrs(['class' => 'select2'])
        ->required()
        !!}
    </div>
    <div class="col-md-4">
        {!! Form::text('balance', 'Saldo:')
        ->attrs(['maxlength' => 8])
        ->required()
        !!}
    </div>
    <div class="col-md-4">
        {!! Form::text('money_change', 'Troco:')
        ->attrs(['maxlength' => 8])
        ->required()
        !!}
    </div>

</div>

<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>

@push('js')
<script>
    $(document).ready(function() {
        $('#inp-balance').mask('000.000,00', {
            reverse: !0
        });
        $('#inp-money_change').mask('000.000,00', {
            reverse: !0
        });
    })

</script>
@endpush
