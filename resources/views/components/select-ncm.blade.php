@props([
    'label' => 'NCM',
    'name' => 'ncm_id',
    'value' => [],
    'required' => true,
])

{!! Form::select($name, $label)->options($value)->attrs(['class' => 'select2'])->required($required) !!}

@push('js')
  <script>
    $("#inp-{{ $name }}").select2({
      minimumInputLength: 3,
      language: "pt-BR",
      placeholder: "Buscar {{ $label }}",
      ajax: {
        cache: true,
        url: getUrl() + '/api/v1/ncms',
        dataType: 'json',
        data: function(params) {
          var query = {
            search: params.term
          }
          return query;
        },
        processResults: function(data) {
          var results = [];
          $.each(data.data, function(i, v) {
            var o = {};
            o.id = v.id;
            o.text = v.description;
            o.value = v.id;
            results.push(o);
          })
          return {
            results: results
          };
        }
      }
    });
  </script>
@endpush
