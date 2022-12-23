(function ($) {
    $.fn.select2.defaults.set("theme", "bootstrap");
    $.fn.select2.defaults.set("language", "pt-BR");

    ("use strict");

    // Loaded via <script> tag, create shortcut to access PDF.js exports.
    var pdfjsLib = window["pdfjs-dist/build/pdf"];
    // The workerSrc property shall be specified.
    pdfjsLib.GlobalWorkerOptions.workerSrc =
        "https://mozilla.github.io/pdf.js/build/pdf.worker.js";

    let url = window.location;

    activeMenu(url);

    loadPdf();

    $body = $("body");
    var $sidebar = $(".sidebar");

    $sidebar.on("show.bs.collapse", ".collapse", function () {
        if (!$(this).hasClass("show")) {
            var $li = $(this).parent();
            var $openNav = $li.siblings().find(".collapse.show");
            $openNav.collapse("hide");
        }
    });

    $(document).on({
        ajaxStart: function () {
            $body.addClass("loading");
        },
        ajaxStop: function () {
            $body.removeClass("loading");
        },
    });

    $("input[required], select[required], textarea[required]")
        .siblings("label")
        .addClass("required");

    $(document).on("focus", ".money", function () {
        $(this).maskMoney({ thousands: ".", decimal: ",", allowZero: true });
        $(this).attr("maxlength", "14");
    });

    $(document).on("focus", ".quantity", function () {
        $(this).mask("0000,00", {
            reverse: true
        });
    });

    $(document).on("focus", ".percentage", function () {
        $(this).maskMoney({
            thousands: ".",
            decimal: ",",
            allowZero: true
        });
        $(this).attr("maxlength", "6");
    });

    $(document).on("change", ".percentage", function () {
        let value = moneyToFloat($(this).val());
        if (value > 100) {
            $(this).val('100,00');
        }
    });

    $("form").on("submit", function () {
        $(this).find(":submit").prop("disabled", true);
    });

    /* start - maxlength dos campos referentes a pessoa*/
    var cpfMascara = function (val) {
        return val.replace(/\D/g, "").length > 11
            ? "00.000.000/0000-00"
            : "000.000.000-009";
    },
        cpfOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(cpfMascara.apply({}, arguments), options);
            },
        };

    var SPMaskBehavior = function (val) {
        return val.replace(/\D/g, "").length === 11
            ? "(00) 00000-0000"
            : "(00) 0000-00009";
    },
        spOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            },
        };

    $(".cpf_cnpj").mask(cpfMascara, cpfOptions);
    $(".cpf").mask("000.000.000-00", { reverse: true });
    $(".cnpj").mask("00.000.000/0000-00", { reverse: true });
    $(".name").prop('maxlength', '100');
    $(".full_name").prop('maxlength', '100');
    $(".state_registration").prop('maxlength', '25');
    $(".city_registration").prop('maxlength', '25');
    $(".email").prop('maxlength', '100');
    $(".phone").mask(SPMaskBehavior, spOptions);
    $(".cep").mask("00000-000");
    $(".address").prop('maxlength', '50');
    $(".district").prop('maxlength', '50');
    $(".number").prop('maxlength', '4');


    /* end - maxlength dos campos referentes a pessoa*/

    $(".select2").select2(
        {
            matcher: matchCustom,
        }
    );

    $("#inp-city_id").select2({
        minimumInputLength: 3,
        language: "pt-BR",
        placeholder: "Selecione a Cidade",
        width: "100%",
        ajax: {
            cache: true,
            url: getUrl() + "/api/v1/cities",
            dataType: "json",
            data: function (params) {
                var query = {
                    search: params.term,
                };
                return query;
            },
            processResults: function (response) {
                var results = [];

                $.each(response.data, function (i, v) {
                    var o = {};
                    o.id = v.id;
                    o.text = v.info;
                    o.value = v.id;
                    results.push(o);
                });
                return {
                    results: results,
                };
            },
        },
    });

    function matchCustom(params, data) {
        // If there are no search terms, return all of the data
        if ($.trim(params.term) === "") {
            return data;
        }

        // Do not display the item if there is no 'text' property
        if (typeof data.text === "undefined") {
            return null;
        }
        // `params.term` should be the term that is used for searching
        // `data.text` is the text that is displayed for the data object
        if (
            data.text
                .toLowerCase()
                .replace(/[^a-zA-Z0-9 ]/g, "")
                .indexOf(params.term.toLowerCase()
                    .replace(/[^a-zA-Z0-9 ]/g, "")) > -1
        ) {
            var modifiedData = $.extend({}, data, true);
            // You can return modified objects from here
            // This includes matching the `children` how you want in nested data sets
            return modifiedData;
        }

        // Return `null` if the term should not be displayed
        return null;
    }

    $("#inp-nif").on("change", function () {
        var nif = $(this).val();

        $.get(getUrl() + "/api/v1/get-person-by-nif?nif=" + nif).done(function (
            response
        ) {
            var person = response.data;

            if (person) {
                $("input")
                    .not($(this))
                    .each(function () {
                        if (person[$(this).attr("name")])
                            $(this).val(person[$(this).attr("name")]);
                    });

                $("#inp-city_id").append(
                    new Option(
                        person.city.info,
                        person.city_id
                    )
                );
                $("#inp-city_id").val(person.city_id);
            }
        });
    });

    $(".btn-delete").on("click", function (e) {
        var form = $(this).parents("form").attr("id");
        swal({
            title: "Você está certo?",
            text: "Uma vez deletado, você não poderá recuperar esse item novamente!",
            icon: "warning",
            buttons: true,
            buttons: ["Cancelar", "Excluir"],
            dangerMode: true,
        }).then((isConfirm) => {
            if (isConfirm) {
                document.getElementById(form).submit();
            }
        });
    });

    $(".multi-select").bootstrapDualListbox({
        nonSelectedListLabel: "Disponíveis",
        selectedListLabel: "Selecionados",
        filterPlaceHolder: "Filtrar",
        filterTextClear: "Mostrar Todos",
        moveSelectedLabel: "Mover Selecionados",
        moveAllLabel: "Mover Todos",
        removeSelectedLabel: "Remover Selecionado",
        removeAllLabel: "Remover Todos",
        infoText: "Mostrando Todos - {0}",
        infoTextFiltered:
            '<span class="label label-warning">Filtrado</span> {0} DE {1}',
        infoTextEmpty: "Sem Dados",
        moveOnSelect: false,
    });

    $(".btn-print").on("click", printPage);

    $('.remove-labels').find('label').remove();

    $(".pdf-input").on("change", function (e) {
        var file = e.target.files[0];
        var canvas = $(this).closest("div").siblings("canvas")[0];
        var context = canvas.getContext("2d");
        var validImageTypes = [
            "image/gif",
            "image/jpeg",
            "image/png",
            "image/jpg",
        ];
        if (file.type == "application/pdf") {
            if (file.size <= 2048000) {
                var fileReader = new FileReader();
                fileReader.onload = function () {
                    var pdfData = new Uint8Array(this.result);
                    // Using DocumentInitParameters object to load binary data.
                    var loadingTask = pdfjsLib.getDocument({
                        data: pdfData,
                    });
                    loadingTask.promise.then(
                        function (pdf) {
                            // Fetch the first page
                            var pageNumber = 1;
                            pdf.getPage(pageNumber).then(function (page) {
                                var scale = 1.5;
                                var viewport = page.getViewport({
                                    scale: scale,
                                });
                                // Prepare canvas using PDF page dimensions
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                // Render PDF page into canvas context
                                var renderContext = {
                                    canvasContext: context,
                                    viewport: viewport,
                                };
                                var renderTask = page.render(renderContext);
                                renderTask.promise.then(function () { });
                            });
                        },
                        function (reason) {
                            console.error(reason);
                        }
                    );
                };
                fileReader.readAsArrayBuffer(file);
            } else {
                $(this).val("");
                canvas.height = 0;
                canvas.width = 0;
                alert("Desculpe, o tamanho do arquivo ultrapassa 2MB");
            }
        } else if (validImageTypes.includes(file.type)) {
            var base_image = new Image();
            base_image.src = URL.createObjectURL(file);
            base_image.onload = function () {
                var context = canvas.getContext("2d");

                context.drawImage(base_image, 0, 0);
            };
        } else {
            $(this).val("");
            canvas.height = 0;
            canvas.width = 0;
            alert("Desculpe, o formato do arquivo deve ser .pdf");
        }
    });
})(jQuery);

function loadPdf() {
    $("canvas").each(function (index) {
        var canvas = $(this)[0];
        var input = $(this).siblings('input[type="hidden"]')[0];
        if (input && input.value) {
            if (isImage(input.value)) {
                var base_image = new Image();
                base_image.src = input.value;
                base_image.onload = function () {
                    var context = canvas.getContext("2d");

                    context.drawImage(base_image, 0, 0);
                };
            } else {
                pdfjsLib
                    .getDocument({
                        url: input.value,
                    })
                    .promise.then(function (pdf) {
                        var context = canvas.getContext("2d");

                        pdf.getPage(1).then(function (page) {
                            var scale = 1.5;
                            var viewport = page.getViewport({
                                scale: scale,
                            });

                            // Prepare canvas using PDF page dimensions
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            // Render PDF page into canvas context
                            var renderContext = {
                                canvasContext: context,
                                viewport: viewport,
                            };
                            var renderTask = page.render(renderContext);
                        });
                    });
            }
        }
    });
}

function isImage(url) {
    return url.match(/\.(jpeg|jpg|gif|png|svg)$/) != null;
}

function getUrl() {
    return document.getElementById("baseurl").value;
}

function moneyToFloat(value) {
    if (!value) {
        return 0;
    }

    var number_without_mask = value.replace(".", "").replace(",", ".");
    return parseFloat(number_without_mask.replace(/[^0-9\.]+/g, ""));
}

function floatToMoney(value) {
    return value.toLocaleString("pt-BR", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

function activeMenu(url) {
    var element = $("ul.nav li a").filter(function () {
        return this.href == url.href || url.href.indexOf(this.href) == 0;
    });

    if (element.hasClass("collapse-item")) {
        element.addClass("active");
    }

    $(element)
        .parents()
        .each(function (index) {
            if (index == 0 && $(this).is("li")) {
                $(this).addClass("active");
            }
            if (this.className.indexOf("collapse") != -1) {
                $(this).addClass("show");
            }
        });
}

function printPage() {
    var divPrint = document.querySelector(".print");

    var myWindow = window.open("", "PRINT", "height=800,width=1200");

    myWindow.document.write(
        "<html><head><title>" + document.title + "</title>"
    );
    myWindow.document.write(
        "<style>@media print{.print{background-color:#fff;height:100%;width:100%;position:fixed;top:0;left:0;margin:0;padding:15px;font-size:14px;line-height:18px}.no-print{visibility:hidden;height:0}}@page{size:25cm 35.7cm;margin:5mm 8mm 5mm 8mm}footer{position:fixed;bottom:0;left:0;right:0}footer img{max-width:3.5rem}table{border-collapse:collapse}.table{width:100%;margin-bottom:1rem;color:#212529}.table td,.table th{padding:.75rem;vertical-align:top;border-top:1px solid #dee2e6}.table thead th{vertical-align:bottom;border-bottom:2px solid #dee2e6}.table tbody+tbody{border-top:2px solid #dee2e6}.table-sm td,.table-sm th{padding:.3rem}.table-borderless tbody+tbody,.table-borderless td,.table-borderless th,.table-borderless thead th{border:0}.text-right{text-align:right!important}.img-fluid{max-width:100%;height:auto}.h1,.h2,.h3,.h4,.h5,.h6,h1,h2,h3,h4,h5,h6{margin-bottom:.5rem;font-weight:500;line-height:1.2}h1,h2,h3,h4,h5,h6{margin-top:0;margin-bottom:.5rem}small{font-size:80%}.float-right{float:right!important}.border-bottom{border-bottom:1px solid #dee2e6!important}.border-top{border-top:1px solid #dee2e6!important}.text-left{text-align:left!important}.table-active,.table-active>td,.table-active>th{background-color:rgba(0,0,0,.075)}.table-active, .table-active > th, .table-active > td {   background-color: rgba(0, 0, 0, 0.075); } th { text-align: left;}.form-control {   display: block;   width: 100%;   height: calc(1.5em + 0.75rem + 2px);   padding: 0.375rem 0.75rem;   font-size: 1rem;   font-weight: 400;   line-height: 1.5;   color: #495057;   background-color: #fff;   background-clip: padding-box;   border: 1px solid #ced4da;   border-radius: 0.25rem;   transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; }   </style>"
    );
    myWindow.document.write("</head><body >");
    myWindow.document.write(divPrint.innerHTML);
    myWindow.document.write("</body></html>");

    myWindow.document.close(); // necessary for IE >= 10
    myWindow.focus(); // necessary for IE >= 10*/

    myWindow.print();

    myWindow.onafterprint = function () {
        myWindow.close();
    };
}
