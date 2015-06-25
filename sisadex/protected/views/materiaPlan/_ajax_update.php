<div id="materia-plan-update-modal-container" >

</div>

<script type="text/javascript">
function update()
 {
  
   var data=$("#materia-plan-update-form").serialize();

  jQuery.ajax({
   type: 'POST',
    url: '<?php echo Yii::app()->createAbsoluteUrl("materia-plan/update"); ?>',
   data:data,
success:function(data){
                if(data!="false")
                 {
                  $('#materia-plan-update-modal').modal('hide');
                  renderView(data);
                  $.fn.yiiGridView.update('materia-plan-grid', {
                     
                         });
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
 
   $('#materia-plan-view-modal').modal('hide');
 var data="id="+id;

  jQuery.ajax({
   type: 'POST',
    url: '<?php echo Yii::app()->createAbsoluteUrl("materia-plan/update"); ?>',
   data:data,
success:function(data){
                 // alert("succes:"+data); 
                 $('#materia-plan-update-modal-container').html(data); 
                 $('#materia-plan-update-modal').modal('show');
              },
   error: function(data) { // if error occured
           alert(JSON.stringify(data)); 
         alert("Error occured.please try again");
    },

  dataType:'html'
  });

}
</script>
