<?php require_once __DIR__ . '/_ajax_create_form_common.php'; ?>

<script type="text/javascript">
    function create() {
        var data = $("#tipoexamen-create-form").serialize();
        data = data + "&Tipoexamen%5Bmateria_id%5D=" + materia_id;
        jQuery.ajax({
            type: 'POST',
            url: '<?php
				echo Yii::app ()->createAbsoluteUrl ( "tipoexamen/create" );
				?>',
            data: data,
            success: function (data) {
                if (data != "false") {
                    //Si se crea un tipo de examen personalizado el codigo de materia no se puede cambiar para evitar inconsistencias.
                    $('#Examen_1_materia_id').attr("disabled", true);
                    $('#tipoexamen-create-modal').modal('hide');
                    //elimino la opcion "Otro..." para ordenar el dropdown y luego agregarlo nuevamente asi queda al final
                    $("option[value='-1']").remove();
                    for (i = 1; i < 11; i++) {
                        //almaceno la opcion seleccionada para luego restaurarla
                        selected_option = $('#Examen_' + i + '_tipoexamen_id').val();
                        $('#Examen_' + i + '_tipoexamen_id').append('<option value="' + data.value + '">' + data.label + '</option>');
                        //Ordena el dropdown por texto
                        $('#Examen_' + i + '_tipoexamen_id').html($('#Examen_' + i + '_tipoexamen_id option').sort(function(x, y) {
                            return $(x).text() < $(y).text() ? -1 : 1;
                        }))
                        $('#Examen_' + i + '_tipoexamen_id').append('<option value="-1">Otro....</option>');
                        //restauro la opcion seleccionada
                        $('#Examen_' + i + '_tipoexamen_id  option[value=' + selected_option + ']').attr('selected', 'selected');
                    }
                    //Selecciono la opcion recien ingresada al sistema
                    $('#' + selector_clicked + ' option[value=' + data.value + ']').attr('selected', 'selected');
                }
                else {
                    $('#tipoexamen-create-modal').modal('hide');
                    bootbox.alert("Ya existe un registro en la base de datos.");
                }
            },
            error: function (data) { // if error occured
                $('#tipoexamen-create-modal').modal('hide');
                bootbox.alert("Se ha producido un error interno. Contacte al administrador.");
            }

        });
    }

    function renderCreateForm() {
        $('#tipoexamen-create-form').each(function () {
            this.reset();
        });
        $('#tipoexamen-view-modal').modal('hide');
        $('#tipoexamen-create-modal').modal({
            show: true
        });
    }
</script>
