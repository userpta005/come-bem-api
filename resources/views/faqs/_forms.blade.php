@push('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

<div class="row">
    <div class="col-md-4">
        {!! Form::select('position', 'Posição')->options(\App\Enums\FaqPosition::all()->prepend('Selecione...', ''))->attrs(['class' => 'select2'])->required() !!}
    </div>
    <div class="col-md-8">
        {!! Form::text('question', 'Pergunta')->required()->attrs(['maxlength' => 40]) !!}
    </div>
    <div class="col-md-12">
        {!! Form::textarea('answer', 'Resposta')->attrs(['class' => 'summernote'])->id('answer')->required() !!}
    </div>
    <div class="col-md-10">
        {!! Form::text('url', 'Saiba Mais')->type('url')->attrs(['maxlength' => 60]) !!}
    </div>
    <div class="col-md-2">
        {!!Form::select('is_active', 'Ativo', activeOptions())
        ->value(isset($item) ? $item->is_active->value : 1)
        ->attrs(['class' => 'select2'])
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#answer').summernote({
                lang: "pt-BR",
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endpush
