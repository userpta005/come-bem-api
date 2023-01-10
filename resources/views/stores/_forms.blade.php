<div class="row">
  <div class="col-md-12">
    <div class="painel-header">
      <div class="row">
        <input type="hidden" name="replicate_products" value="">
        <div class="col-md-12">
          {!! Form::select('tenant_id', 'Contratantes')->options($tenants->prepend('Selecione...', ''), 'info')->attrs(['class' => 'select2'])->readonly(!empty($item))->required() !!}
        </div>
        <div class="col-md-3">
          {!! Form::text('nif', 'CPF/CNPJ')->attrs(['class' => 'cpf_cnpj'])->readonly(isset($item))->required() !!}
        </div>
        <div class="col-md-5">
          {!! Form::text('name', 'Nome Completo/Razão Social')->attrs(['class' => 'name'])->required() !!}
        </div>
        <div class="col-md-4">
          {!! Form::text('full_name', 'Nome Social/Nome Fantasia')->attrs(['class' => 'full_name'])->required() !!}
        </div>
        <div class="col-md-3">
          {!! Form::text('state_registration', 'Inscrição Estadual')->attrs(['class' => 'state_registration']) !!}
        </div>
        <div class="col-md-3">
          {!! Form::text('city_registration', 'Inscrição Municipal')->attrs(['class' => 'city_registration']) !!}
        </div>
        <div class="col-md-3">
          {!! Form::date('birthdate', 'DT. Nasc/Abertura')->required() !!}
        </div>
        <div class="col-md-3">
          {!! Form::select('status', 'Status')->options(\App\Enums\StoreStatus::all()->prepend('Selecione...', ''))->value($item->status->value ?? \App\Enums\StoreStatus::ENABLED)->attrs(['class' => 'select2'])->required() !!}
        </div>
        <div class="col-md-5">
          {!! Form::text('email', 'Email')->type('email')->attrs(['class' => 'email'])->readonly(isset($item))->required() !!}
        </div>
        <div class="col-md-3">
          {!! Form::text('phone', 'Telefone')->attrs(['class' => 'phone'])->required() !!}
        </div>
        <div class="col-md-4">
          {!! Form::select('city_id', 'Cidade')->options(isset($item) ? [$item->city_id => $item->city] : [])->required() !!}
        </div>
        <div class="col-md-2">
          {!! Form::text('zip_code', 'CEP')->attrs(['class' => 'cep'])->required() !!}
        </div>
        <div class="col-md-5">
          {!! Form::text('address', 'Endereço')->attrs(['class' => 'address'])->required() !!}
        </div>
        <div class="col-md-3">
          {!! Form::text('district', 'Bairro')->attrs(['class' => 'district']) !!}
        </div>
        <div class="col-md-2">
          {!! Form::text('number', 'Número')->attrs(['class' => 'number']) !!}
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <div class="painel-body">
      <div class="row">
        <div class="col-md-4">
          {!! Form::text('latitude', 'Latitude')->attrs(['maxlength' => 60])->required() !!}
        </div>
        <div class="col-md-4">
          {!! Form::text('longitude', 'Longitude')->attrs(['maxlength' => 60])->required() !!}
        </div>
        <div class="col-md-4">
          {!! Form::text('pix_key', 'Chave  PIX')->attrs(['maxlength' => 60])->required() !!}
        </div>
        <div class="col-md-4">
          {!! Form::text('bank', 'Banco')->attrs(['maxlength' => 20])->required() !!}
        </div>
        <div class="col-md-4">
          {!! Form::text('agency', 'Agência')->attrs(['maxlength' => 20])->required() !!}
        </div>
        <div class="col-md-4">
          {!! Form::text('account', 'Conta')->attrs(['maxlength' => 20])->required() !!}
        </div>
        <div class="col-md-3">
          {!! Form::text('whatsapp', 'Whatsapp')->attrs(['class' => 'phone'])->required() !!}
        </div>
        <div class="col-md-2">
          {!! Form::select('type', 'Tipo')->options(\App\Enums\StoreType::all()->prepend('Selecione...', ''))->attrs(['class' => 'select2'])->required() !!}
        </div>
        <div class="col-md-7">
          {!! Form::text('email_digital', 'Email Conta Digial')->type('email')->attrs(['class' => 'email'])->readonly(session()->has('tenant'))->required() !!}
        </div>
        <div class="col-md-2">
          {!! Form::select('signature', 'Assinatura')->options(\App\Enums\TenantSignature::all()->prepend('Selecione...', ''))->attrs(['class' => 'select2'])->readonly(session()->has('tenant'))->required() !!}
        </div>
        <div class="col-md-3">
          {!! Form::date('dt_accession', 'Dt. Adesão')->readonly(session()->has('tenant'))->readonly(session()->has('tenant'))->required() !!}
        </div>
        <div class="col-md-3">
          {!! Form::date('due_date', 'Dt. Vigência')->readonly(session()->has('tenant'))->readonly(session()->has('tenant'))->required() !!}
        </div>
        <div class="col-md-2">
          {!! Form::select('due_day', 'Dt. Vencimento')->options(\App\Enums\TenantDueDays::all()->prepend('Selecione...', ''))->attrs(['class' => 'select2'])->readonly(session()->has('tenant'))->required() !!}
        </div>
        <div class="col-md-2">
          {!! Form::text('number_equipment', 'N. Equipamentos')->type('number')->min(0)->max(100)->readonly(session()->has('tenant'))->required() !!}
        </div>
        <div class="col-md-2">
          {!! Form::text('lending_value', 'Vl. Comodato (R$)')->attrs(['class' => 'money'])->value(floatToMoney($item->value ?? 0))->readonly(session()->has('tenant'))->required() !!}
        </div>
        <div class="col-md-2">
          {!! Form::text('pix_rate', 'TX Pix')->attrs(['class' => 'percentage'])->readonly(session()->has('tenant'))->required() !!}
        </div>
        <div class="col-md-2">
          {!! Form::text('card_rate', 'TX Cartão')->attrs(['class' => 'percentage'])->readonly(session()->has('tenant'))->required() !!}
        </div>
        <div class="col-md-12">
          {!! Form::textarea('observation', 'Observação')->attrs(['maxlength' => '200'])->readonly(session()->has('tenant'))->required() !!}
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
