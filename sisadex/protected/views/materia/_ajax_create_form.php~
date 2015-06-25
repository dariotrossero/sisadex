
    <div id='materia-create-modal' class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Nueva materia</h3>
    </div>
    
    <div class="modal-body">
    
    <div class="form">

   <?php
   
         $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'materia-create-form',
	'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'method'=>'post',
        'action'=>array("materia/create"),
	'type'=>'horizontal',
	'htmlOptions'=>array(
	                        'onsubmit'=>"return false;",/* Disable normal form submit */
                            ),
          'clientOptions'=>array(
                    'validateOnType'=>true,
                    'validateOnSubmit'=>true,
                    'afterValidate'=>'js:function(form, data, hasError) {
                                     if (!hasError)
                                        {    
                                          create();
                                        }
                                     }'
                                    

            ),                  
  
)); ?>
     	<fieldset>
		
			<p class="note">Campos obligatorios <span class="required">*</span></p>
		

	<?php echo $form->errorSummary($model,null, null,array('class'=>'alert alert-error span12')); ?>
        		
   <div class="control-group">		
			<div class="span4">
			

           <div class="row">
            <?php echo $form->labelEx($model,'id'); ?>
            <?php echo $form->textField($model,'id',array('size'=>10,'maxlength'=>10,'class'=>'span1')); ?>
            <?php echo $form->error($model,'id'); ?>
          </div>

							  <div class="row">
					  <?php echo $form->labelEx($model,'nombreMateria'); ?>
					  <?php echo $form->textField($model,'nombreMateria',array('size'=>70,'maxlength'=>70,'class'=>'span5')); ?>
					  <?php echo $form->error($model,'nombreMateria'); ?>
				  </div>

			  
                        </div>   
  </div>

  </div><!--end modal body-->
  
  <div class="modal-footer">
	<div>

		   <?php
 $this->widget('bootstrap.widgets.TbButton', array(
      
                        'icon'=>'remove',  
      'label'=>'Cerrar',
       'htmlOptions' => array( 
        'onclick' => "$('#materia-create-modal').modal('hide')",
        )  
    )); ?>

    <?php
    
     $this->widget('bootstrap.widgets.TbButton', array(
      'buttonType'=>'submit',
      'type'=>'primary',
                        'icon'=>'ok white', 
      'label'=>$model->isNewRecord ? 'Crear' : 'Guardar',
      )
      
    );
    
    ?>  
	</div> 
   </div><!--end modal footer-->	
</fieldset>

<?php
 $this->endWidget(); ?>

</div>

</div><!--end modal-->

<script type="text/javascript">
function create()
 {
 
   var data=$("#materia-create-form").serialize();
     


  jQuery.ajax({
   type: 'POST',
    url: '<?php
 echo Yii::app()->createAbsoluteUrl("materia/create"); ?>',
   data:data,
success:function(data){
                //alert("succes:"+data); 
                if(data!="false")
                 {
                  $('#materia-create-modal').modal('hide');
                  
                    $.fn.yiiGridView.update('materia-grid', {
                     
                         });
                   
                 }
                 
              },
   error: function(data) { // if error occured
        //    alert(JSON.stringify(data)); 
      bootbox.alert("Se ha producido un error interno. Contacte al administrador.");
       
    },

  dataType:'html'
  });

}

function renderCreateForm()
{
  $('#materia-create-form').each (function(){
  this.reset();
   });

  
  $('#materia-view-modal').modal('hide');
  
  $('#materia-create-modal').modal({
   show:true,
   
  });
}

</script>
