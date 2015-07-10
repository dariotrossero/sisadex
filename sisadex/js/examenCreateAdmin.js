getTipos = function () {
    var codigoMateria = $('#Examen_1_materia_id').val();        // el "value" de ese <option> seleccionado
    if (codigoMateria == 'materia_id') {
        $('#siguiente').slideUp('fast');
        return;
    }
    var action = 'GetTipos/id/' + codigoMateria;
    $('#reportarerror').html("");
    $.getJSON(action, function (listaJson) {
        for (var i = 1; i <= 10; i++) {
            $('#Examen_' + i.toString() + '_tipoexamen_id').find('option').each(function () {
                $(this).remove();
            });
            $.each(listaJson, function (key, tipoExamen) {
                $('#Examen_' + i.toString() + '_tipoexamen_id').append("<option value='" + tipoExamen.id + "'>"
                + tipoExamen.nombreTipoExamen + "</option>");
            });
            $('#Examen_' + i.toString() + '_tipoexamen_id').append("<option value='-1'>Otro...</option>");
        };
        $('#siguiente').slideDown('fast');
    }).error(function (e) {
        $('#reportarerror').html(e.responseText);
    });
};

$('#Examen_1_materia_id').change(getTipos);
