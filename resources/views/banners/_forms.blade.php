<div class="row">
    <div class="col-3">
        <x-img :value="$item->image_url"/>
    </div>
    <div class="col-9">
        <div class="row">
            <div class="col-md-8">
                {!!Form::text('name', 'Nome')
                ->attrs(['maxlength' => 100])
                ->required()
                !!}
            </div>
            <div class="col-md-4">
                {!!Form::text('sequence', 'Sequência')
                ->type('number')
                ->min(1)
                ->required()
                !!}
            </div>
            <div class="col-md-4">
                {!!Form::select('type', 'Tipo', \App\Enums\BannerType::all()->prepend('Selecione...', ''))
                ->attrs(['class' => 'select2'])
                ->required()
                !!}
            </div>
            <div class="col-md-4">
                {!!Form::select('position', 'Posição', \App\Enums\BannerPosition::all()->prepend('Selecione...', ''))
                ->attrs(['class' => 'select2'])
                ->required()
                !!}
            </div>
            <div class="col-md-4">
                {!!Form::select('status', 'Status', \App\Enums\Common\Status::all())
                ->value($item->status->value ?? \App\Enums\Common\Status::ACTIVE)
                ->attrs(['class' => 'select2'])
                ->required()
                !!}
            </div>
            <div class="col-md-12">
                {!!Form::text('url', 'URL')
                ->attrs(['maxlength' => 250])
                ->required(false)
                !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>

@include('banners._modal')
