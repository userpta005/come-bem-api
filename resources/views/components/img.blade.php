@props([
    'formats' => 'jpeg,png,jpg,gif,svg',
    'value' => asset('images/noimage.png'),
    'name' => 'image',
    'label' => 'Imagem',
    'required' => false,
])

<div class="form-group">
  <label for="inp-{{ $name }}">{{ $label }}</label>
  <img src="{{ $value }}"
    class="d-block"
    id="preview-{{ $name }}"
    style="max-width: 100%; max-height: 100%; border: 1px solid black;">
  <input type="file"
    id="inp-{{ $name }}"
    name="{{ $name }}"
    value="{{ $value }}"
    class="d-none {{ $errors->has($name) ? 'is-invalid' : '' }}"
    {{ $required ? 'required' : '' }}>
  <label for="inp-{{ $name }}"
    class="text-primary cp">Trocar</label>
  @if ($errors->has($name))
    <div class="invalid-feedback">{{ $errors->first($name) }}</div>
  @endif
</div>

@push('js')
  <script>
    $("#inp-{{ $name }}").on("change", function() {
      let file = this.files[0];
      let extension = $(this).val().substring($(this).val().lastIndexOf(".") + 1).toLowerCase();
      let string = '{{ $formats }}'.replace(/\s/g, '');
      let formats = string.split(',');
      let validFormat = formats.find(element => element == extension);

      if (validFormat) {
        if (this.files && this.files[0]) {
          var reader = new FileReader();
          reader.readAsDataURL(this.files[0]);
          reader.onload = function(e) {
            $("#preview-{{ $name }}").prop("src", e.target.result);
          };
        }
        return true;
      }

      swal(
        "Atenção",
        "Formato do incompatível. Deve ser (" + string + ').',
        "warning"
      );
    });
  </script>
@endpush
