
fillTipoPersonalizado = function (elemento) {
    selector_clicked = elemento.id;
    var opcionSeleccionada = $(elemento);                        // el <option> seleccionad
    var num = opcionSeleccionada.context.id.replace(/^.*(\d+).*$/i, '$1');
    var codigoExamen = opcionSeleccionada.val();        // el "value" de ese <option> seleccionado
    if (codigoExamen == -1)  {

        if (materia_id!="") {
        $('#tipoexamen-create-form').each(function () {
            this.reset();
        });
        $('#tipoexamen-view-modal').modal('hide');
        $('#tipoexamen-create-modal').modal({
            show: true
        });

        return;
    }
        else
        {
            bootbox.alert("Seleccione una materia primero.");
        }
    }

};
