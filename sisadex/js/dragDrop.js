function dragMateria(materia, event) {
    event.dataTransfer.setData('Materia', materia.id);
}

function dropMateria(target, event) {
    object_changed=true;
    var materia = event.dataTransfer.getData('materia');
    target.appendChild(document.getElementById(materia));
    div_target = target.id;
}

var object_changed;
    $('#Plan_anioPlan, #Plan_Carrera_id').change(function () {
        object_changed=true;
        $(".alert-error").slideUp('fast');
        var anioPlan = $('#Plan_anioPlan').val();  // el "value" de ese <option> seleccionado
        var Carrera_id = $('#Plan_Carrera_id').val();
        console.log(anioPlan);
        console.log(Carrera_id);
        var action = 'index?r=plan/TestExistsPlan&anioPlan=' + anioPlan + '&Carrera_id=' + Carrera_id;
        $('#reportarerror').html("");
        $.getJSON(action, function (respuesta) {
            if (respuesta == "true") {
                $('#msjError').slideDown('fast');
            }
            else {
                $('#msjError').slideUp('fast');
            }
        }).error(function (e) {
            $('#reportarerror').html(e.responseText);
        });
    });
    
    
$('a:not(.skip)').click(function(e) {
    if (object_changed) {
    e.preventDefault();
    bootbox.confirm("<img src='" + baseUrl + "/images/warning.png'/>  No se han guardado los cambios, si continua se perderan todos los datos <br/><br/>¿Continuar de todas formas? ", function (result) {
            if (result)           
              window.location.replace(e.currentTarget.href);
            
        })
    
}
});