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
                    for (i = 1; i < 11; i++) {
                        $('#Examen_' + i + '_tipoexamen_id').append('<option value="' + data.value + '">' + data.label + '</option>');
                    }
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
