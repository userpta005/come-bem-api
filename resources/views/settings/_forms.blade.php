<div class="row">
  <div class="col-md-4">
    <x-img name="logo"
      :value="$settings->logo ?? null" />
  </div>
  <div class="col-md-4">
    <label>Termos de Uso</label>
    <input type="hidden"
      value="{{ isset($settings) && $settings->terms ? asset('storage/' . $settings->terms . '?version=' . uniqid()) : null }}">
    <canvas class="pdf-miniatura"
      style="width:300px; height:300px"></canvas>
    <div class="buttons-relatorio">
      <label class="btn btn-block btn-primary btn-gerar-relatorio">
        Anexar em formato PDF. Tam. Até 2 MB;
        <input class="custom-file-input form-control pdf-input @if ($errors->has('terms')) is-invalid @endif"
          style="display: none;"
          name="terms"
          type="file" /><br>
      </label>
      @if (isset($settings) && $settings->terms)
        <a href="{{ asset('storage/' . $settings->terms . '?version=' . uniqid()) }}"
          target="_blank"
          class="btn btn-block">Visualizar
          Anexo</a>
      @endif
    </div>
  </div>

  <div class="col-md-4">
    <label>Política de Privacidade</label>
    <input type="hidden"
      value="{{ isset($settings) && $settings->privacy_policy ? asset('storage/' . $settings->privacy_policy . '?version=' . uniqid()) : null }}">
    <canvas class="pdf-miniatura"
      style="width:300px; height:300px"></canvas>
    <div class="buttons-relatorio">
      <label class="btn btn-block btn-primary btn-gerar-relatorio">
        Anexar em formato PDF. Tam. Até 2 MB;
        <input class="custom-file-input form-control pdf-input @if ($errors->has('privacy_policy')) is-invalid @endif"
          style="display: none;"
          name="privacy_policy"
          type="file" /><br>
      </label>
      @if (isset($settings) && $settings->privacy_policy)
        <a href="{{ asset('storage/' . $settings->privacy_policy . '?version=' . uniqid()) }}"
          target="_blank"
          class="btn btn-block">Visualizar
          Anexo</a>
      @endif
    </div>
  </div>

  <div class="col-12 mt-1">
    <div class="card">
      <div class="card-header bg-secondary">
        Informações do Cliente
      </div>
      <div class="card-body shadow-sm">
        <div class="row">
          <div class="col-md-3">
            {!! Form::text('nif', 'CPF/CNPJ')->attrs(['class' => 'cpf_cnpj'])->required() !!}
          </div>
          <div class="col-md-5">
            {!! Form::text('full_name', 'Razão Social')->attrs(['maxlength' => 50])->required() !!}
          </div>
          <div class="col-md-4">
            {!! Form::text('name', 'Nome Fantasia')->attrs(['maxlength' => 35])->required() !!}
          </div>
          <div class="col-md-6">
            {!! Form::text('email', 'Email')->attrs(['maxlength' => 50])->required() !!}
          </div>
          <div class="col-md-3">
            {!! Form::text('phone', 'Telefone')->attrs(['class' => 'phone'])->required() !!}
          </div>
          <div class="col-md-3">
            {!! Form::select('status', 'Status')->options(\App\Enums\SettingsStatus::all()->prepend('Selecione...', ''))->attrs(['class' => 'select2'])->value(isset($settings) ? $settings->status->value : '')->required() !!}
          </div>
          <div class="col-md-2">
            {!! Form::text('zip_code', 'CEP')->attrs(['class' => 'cep'])->required() !!}
          </div>
          <div class="col-md-8">
            {!! Form::text('address', 'Endereço')->attrs(['maxlength' => 50])->required() !!}
          </div>
          <div class="col-md-2">
            {!! Form::text('number', 'No.')->attrs(['maxlength' => 5])->required() !!}
          </div>
          <div class="col-md-3">
            {!! Form::text('district', 'Bairro')->attrs(['maxlength' => 35]) !!}
          </div>
          <div class="col-md-3">
            {!! Form::select(
                'city_id',
                'Cidade',
                isset($settings) ? [$settings->city_id => $settings->city->title . ' - ' . $settings->city->letter] : [],
            )->required() !!}
          </div>
          <div class="col-md-3">
            {!! Form::text('maps', 'Local (Google maps)')->attrs(['maxlength' => 150]) !!}
          </div>
          <div class="col-md-3">
            {!! Form::text('contact', 'Contato')->attrs(['maxlength' => 30]) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 mt-1">
    <div class="card">
      <div class="card-header bg-secondary">
        Instagram
      </div>
      <div class="card-body shadow-sm">
        <div class="row">
          <div class="col-md-6">
            {!! Form::text('instagram_url', 'Instagram (URL)')->attrs(['maxlength' => 80]) !!}
          </div>
          <div class="col-md-3">
            {!! Form::text('instagram_user', 'Instagram (user)')->attrs(['maxlength' => 40]) !!}
          </div>
          <div class="col-md-3">
            {!! Form::text('instagram_senha', 'Instagram (password)')->type('password')->attrs(['maxlength' => 15]) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 mt-1">
    <div class="card">
      <div class="card-header bg-secondary">
        Facebook
      </div>
      <div class="card-body shadow-sm">
        <div class="row">
          <div class="col-md-6">
            {!! Form::text('facebook_url', 'Facebook (URL)')->attrs(['maxlength' => 80]) !!}
          </div>
          <div class="col-md-3">
            {!! Form::text('facebook_user', 'Facebook (user)')->attrs(['maxlength' => 40]) !!}
          </div>
          <div class="col-md-3">
            {!! Form::text('facebook_senha', 'Facebook (password)')->type('password')->attrs(['maxlength' => 15]) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 mt-1">
    <div class="card">
      <div class="card-header bg-secondary">
        Youtube
      </div>
      <div class="card-body shadow-sm">
        <div class="row">
          <div class="col-md-6">
            {!! Form::text('youtube_url', 'Youtube (URL)')->attrs(['maxlength' => 80]) !!}
          </div>
          <div class="col-md-3">
            {!! Form::text('youtube_user', 'Youtube (user)')->attrs(['maxlength' => 40]) !!}
          </div>
          <div class="col-md-3">
            {!! Form::text('youtube_senha', 'Youtube (password)')->type('password')->attrs(['maxlength' => 15]) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 mt-1">
    <div class="card">
      <div class="card-header bg-secondary">
        Twitter
      </div>
      <div class="card-body shadow-sm">
        <div class="row">
          <div class="col-md-6">
            {!! Form::text('twitter_url', 'Twitter (URL)')->attrs(['maxlength' => 80]) !!}
          </div>
          <div class="col-md-3">
            {!! Form::text('twitter_user', 'Twitter (user)')->attrs(['maxlength' => 40]) !!}
          </div>
          <div class="col-md-3">
            {!! Form::text('twitter_senha', 'Twitter (password)')->type('password')->attrs(['maxlength' => 15]) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 mt-1">
    <div class="card">
      <div class="card-header bg-secondary">
        SEO
      </div>
      <div class="card-body shadow-sm">
        <div class="row">
          <div class="col-md-6">
            {!! Form::textarea('pixels', 'Pixels')->attrs(['maxlength' => 80]) !!}
          </div>
          <div class="col-md-6">
            {!! Form::textarea('ads', 'ADS')->attrs(['maxlength' => 80]) !!}
          </div>
          <div class="col-md-6">
            {!! Form::textarea('meta_tags', 'Meta tags')->attrs(['maxlength' => 200]) !!}
          </div>
          <div class="col-md-6">
            {!! Form::textarea('footer', 'Rodapé')->attrs(['maxlength' => 200]) !!}
          </div>
          <div class="col-md-12">
            {!! Form::textarea('note', 'Observação')->attrs(['maxlength' => 200]) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <button type="submit"
      class="btn btn-success float-right mt-4">Salvar</button>
  </div>
</div>
