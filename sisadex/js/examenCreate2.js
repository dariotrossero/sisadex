fillTipoPersonalizado=function (elemento){

                var opcionSeleccionada = $(elemento);                        // el <option> seleccionado
                

                var num = opcionSeleccionada.context.id.replace(/^.*(\d+).*$/i,'$1');
              
                var codigoExamen = opcionSeleccionada.val();        // el "value" de ese <option> seleccionado
              
                 if(codigoExamen == -1) {
                         $('#tipoPersonalizado_'+num.toString()).slideDown('fast');
                         return;
                 } else  {
                           $('#tipoPersonalizado_'+num.toString()).slideUp('fast');
                         return;
                 }
        };

