<div class="modal fade" id="cash-movements" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><strong>Movimento do Caixa</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open()->post()->route('cash-movements.painel')->multipart() !!}
                @include('cash-movements._forms_painel')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>