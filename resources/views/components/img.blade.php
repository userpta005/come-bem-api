@props(['formats' => 'jpeg,png,jpg,gif,svg', 'value' => asset('images/noimage.png'), 'name' => 'image', 'label' => 'Imagem'])

<div class="row">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-12">
        <label>{{ $label }}</label>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <img src="{{ $value }}"
          class="d-block"
          id="preview-image"
          style="max-width: 100%; max-height: 100%; border: 1px solid black;">
        <input type="file"
          id="image"
          name="{{ $name }}"
          {{ $attributes->merge(['class' => "d-none @if ($errors->has($name)) is-invalid @endif"]) }}>
        <label for="image"
          class="text-primary cp">Trocar</label>
        @if ($errors->has($name))
          <div class="invalid-feedback">{{ $errors->first($name) }}</div>
        @endif
      </div>
    </div>
  </div>
</div>

@once
  @push('js')
    <script>
      $("#image").on("change", function() {
        let file = this.files[0];
        let extension = $(this).val().substring($(this).val().lastIndexOf(".") + 1).toLowerCase();
        let string = '{{ $formats }}'.replace(/\s/g, '');
        let formats = string.split(',');
        let validFormat = formats.find(element => element == extension);

        if (validFormat) {
          changeImage(this);
          return true;
        }

        swal(
          "Atenção",
          "Formato do incompatível. Deve ser (" + string + ').',
          "warning"
        );
      });

      function changeImage(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.readAsDataURL(input.files[0]);
          reader.onload = function(e) {
            $("#preview-image").prop("src", e.target.result);
            $("#remove-image").removeClass('d-none');
          };
        }
      }
    </script>
  @endpush
@endonce
