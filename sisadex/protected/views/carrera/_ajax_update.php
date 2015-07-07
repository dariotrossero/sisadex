<div id="carrera-update-modal-container">

</div>

<script type="text/javascript">
    function update() {
        var data = $("#carrera-update-form").serialize();
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("carrera/update"); ?>',
            data: data,
            success: function (data) {
                if (data == "true") {
                    $('#carrera-update-modal').modal('hide');
                    $.fn.yiiGridView.update('carrera-grid', {});
                }
                else {
                    if (data == "exists") {
                        $('#carrera-update-modal').modal('hide');
                        bootbox.alert("Ya existe un registro en la base de datos.");
                    }
                    if (data == "false") {
                        $('#carrera-update-modal').modal('hide');
                        bootbox.alert("<div id='error-modal' class='alert alert-error spa6'>Por favor corrija los siguientes errores de ingreso:<ul><li>El campo no puede ser vacio.</li></ul></div>");
                    }
                }
            },
            error: function (data) { // if error occured
                bootbox.alert("Se ha producido un error interno. Contacte al administrador.");
            },
            dataType: 'html'
        });
    }

    function renderUpdateForm(id) {
        $('#carrera-view-modal').modal('hide');
        var data = "id=" + id;
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo Yii::app()->createAbsoluteUrl("carrera/update"); ?>',
            data: data,
            success: function (data) {
                $('#carrera-update-modal-container').html(data);
                $('#carrera-update-modal').modal('show');
            },
            error: function (data) { // if error occured
                $('#carrera-view-modal').modal('hide');
                bootbox.alert("Se ha producido un error interno. Contacte al administrador.");
            },
            dataType: 'html'
        });
    }
</script>
