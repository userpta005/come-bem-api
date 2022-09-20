<button type="button" class="btn btn-success btn-add-item mt-3">
    <i class="fas fa-plus"></i>
    Adicionar
</button>

@once
@push('js')
<script>
    $(".btn-add-item").on("click", function () {
        let tbody = $(this).closest(".row").prev().find("tbody");
        let tr = tbody.find("tr").last();
        let isValid = true;
        let requireds = tr.find(':input[required]').each(function () {
            if(!$(this).val()){
                isValid = false;
            }
        });

        if (isValid) {
            $("tbody select.select2").select2("destroy");
            let clone = tr.clone();
            clone.find(".old-item").remove();
            clone.find("input, select").not(".ignore").val("");
            tbody.append(clone);
            setTimeout(function () {
                $("tbody select.select2").select2({
                    language: "pt-BR",
                    width: "100%",
                });
            }, 100);
            return true;
        }

        swal(
            "Atenção",
            "Preencha todos os campos obrigatórios",
            "warning"
        );
    });
</script>
@endpush
@endonce