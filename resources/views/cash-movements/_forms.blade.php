<div class="row">
    <div class="col-md-4">
        {!! Form::select('cashier_id', 'Caixa:')
        ->options($cashiers->prepend('Selecione...', ''), 'description')
        ->attrs(['class' => 'select2'])
        ->required()
        !!}
    </div>
    <div class="col-md-4">
        {!!Form::select('movement_type_id', 'Tipo de Movimento:')
        ->options($movement_types->prepend('Selecione...', ''), 'name')
        ->attrs(['class' => 'select2'])
        ->required()
        !!}
    </div>
    <div class="col-md-4">
        {!!Form::select('payment_method_id', 'Forma de Pagamento:')
        ->options($payment_methods->prepend('Selecione...', ''))
        ->attrs(['class' => 'select2'])
        ->required()
        !!}
    </div>
    <div class="col-md-8">
        {!!Form::select('client_id', 'Cliente:')
        ->options($clients->prepend('Selecione...', ''), 'info')
        ->attrs(['class' => 'select2'])
        ->required(false)
        !!}
    </div>
    <div class="col-md-4">
        {!! Form::text('amount', 'Valor:')
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
        $('#inp-amount').mask('000.000,00', {
            reverse: !0
        });
    })

    $('#inp-movement_type_id').on('change', function() {

        var movement_type = $('#inp-movement_type_id').val()

        if(movement_type == 1){
            $('#inp-client_id').prop('required', true)
        }else{
            $('#inp-client_id').prop('required', false)
        }
    });

</script>
@endpush