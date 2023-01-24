<div class="row">
    <div class="col-md-4">
        {!! Form::text('description', 'Descrição')->attrs(['class' => 'description', 'maxlength' => 20])->required() !!}
    </div>
    <div class="col-md-2">
        {!! Form::text('code', 'Sigla')->attrs(['class' => 'code', 'maxlength' => 4])->required() !!}
    </div>
    <div class="col-md-3">
        {!! Form::select('status', 'Status', [null => 'Selecione...'] + \App\Models\Cashier::allStatus())->attrs(['class' => 'select2'])->required() !!}
    </div>
    <div class="col-md-3">
        {!! Form::text('balance', 'Saldo')
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
    })

</script>
@endpush
