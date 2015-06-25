 
        // creamos un evento onchange para cuando el usuario cambie su seleccion
        // importante:  #combo1 hace referencia al ID indicado arriba con: array('id'=>'combo1')
        //


getTipos=function(){
               
               var codigoMateria = $('#Examen_1_materia_id').val();        // el "value" de ese <option> seleccionado
               
                if(codigoMateria == 'materia_id') {
                        $('#siguiente').slideUp('fast');
                        return;
                }
                
                var action = 'index.php?r=examen/GetTipos&id='+codigoMateria;


                // se pide al action la lista de productos de la categoria seleccionada
                //
                $('#reportarerror').html("");
                $.getJSON(action, function(listaJson) {
                        //
                        // el action devuelve los productos en su forma JSON, el iterador "$.each" los separará.
                        //
                        
                        // limpiar el combo productos
                    
                    for (var i = 1; i <=10; i++) {
                        
                 


                        $('#Examen_'+i.toString()+'_tipoexamen_id').find('option').each(function(){ $(this).remove(); });
                         
                        $.each(listaJson, function(key, tipoExamen) {                                //
                                // "producto" es un objeto JSON que representa al modelo Producto
                                // por tanto una llamada a: alert(producto.nombre) dira: "camisas"
                                $('#Examen_'+i.toString()+'_tipoexamen_id').append("<option value='"+tipoExamen.id+"'>"
                                        +tipoExamen.nombreTipoExamen+"</option>");
                        });
                        $('#Examen_'+i.toString()+'_tipoexamen_id').append("<option value='-1'>Otro...</option>");


                    };

                        
                        $('#siguiente').slideDown('fast');
                }).error(function(e){ $('#reportarerror').html(e.responseText); });                
        };


$(document).ready(function ()  {

getTipos.apply();

});
$('#Examen_1_materia_id').change(getTipos);

        
       