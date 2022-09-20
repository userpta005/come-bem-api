@props(['label' => null , 'formats' => [], 'behavior' => null])

<div class="mb-3">
    <label class="form-label">{{$label}}</label>
    <input type="file" {{$attributes->merge(['class' => 'form-control file'])}}>
</div>

@once
@push('js')
<script>
    $('.file').on('change', function () {
        let file = this.files[0];
        let extension = $(this).val().substring($(this).val().lastIndexOf(".") + 1).toLowerCase();
        let string = '{{$formats}}'.replace(/\s/g,'');
        let formats = string.split(',');

        let validFormat = formats.find(element => element == extension);

        if (!validFormat) {
            swal(
                "Atenção",
                "Formato do incompatível. Deve ser (" + string + ').',
                "warning"
            );
            $(this).val(null);
        }
    });
</script>
@endpush
@endonce