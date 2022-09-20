<div class="form-group">
    <button type="button" class="btn-danger btn-sm btn-remove-item cp">
        <i class="fas fa-trash"></i>
    </button>
</div>

@once
@push('js')
<script>
    $("body").on("click", ".btn-remove-item", "click", function (e) {
        e.preventDefault();
        swal({
            title: "Você esta certo?",
            text: "Deseja remover esse item mesmo?",
            icon: "warning",
            buttons: true,
        }).then((willDelete) => {
            let trLength = $(this).closest("tbody").find("tr").length;
            if (trLength > 1) {
                $(this).closest("tr").remove();
                return true;
            }
            swal(
                "Atenção",
                "Você deve ter ao menos um item na lista",
                "warning"
            );
        });
    });
</script>
@endpush
@endonce