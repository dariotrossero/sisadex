<div id="materia-update-modal-container" >

</div>

<script type="text/javascript">
function update()
 {
  
   var data=$("#materia-update-form").serialize();

  jQuery.ajax({
   type: 'POST',
    url: '<?php echo Yii::app()->createAbsoluteUrl("materia/update"); ?>',
   data:data,
success:function(data){
  console.log(data);
                if(data=="true")
                 {
                  $('#materia-update-modal').modal('hide');
                  //renderView(data);
                  $.fn.yiiGridView.update('materia-grid', {
                     
                         });
                 }  else
                 {
                  if (data=="exists") {
                    $('#materia-update-modal').modal('hide');
                     bootbox.alert("Ya existe un registro en la base de datos.");
                 }
                  if (data=="false") {
                  $('#materia-update-modal').modal('hide');
                   bootbox.alert("<div id='error-modal' class='alert alert-error spa6'>Por favor corrija los siguientes errores de ingreso:<ul><li>Los campos no pueden ser vacios.</li><li>Complejidad debe ser un número entre 1 y 10</li></ul></div>");  
                 }
               }
                 
              },
   error: function(data) { // if error occured
          alert(JSON.stringify(data)); 

    },

  dataType:'html'
  });

}

function renderUpdateForm(id)
{
 
   $('#materia-view-modal').modal('hide');
 var data="id="+id;

  jQuery.ajax({
   type: 'GET',
    url: '<?php echo Yii::app()->createAbsoluteUrl("materia/update"); ?>',
   data:data,
success:function(data){
                 // alert("succes:"+data); 
                 $('#materia-update-modal-container').html(data); 
                 $('#materia-update-modal').modal('show');
              },
   error: function(data) { // if error occured
       //    alert(JSON.stringify(data)); 
     $('#materia-view-modal').modal('hide');
         bootbox.alert("Se ha producido un error interno. Contacte al administrador.");
    },

  dataType:'html'
  });

}
</script>
