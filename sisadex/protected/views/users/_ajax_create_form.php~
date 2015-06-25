
    <div id='users-create-modal' class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4>Nuevo usuario</h4>
    </div>
    
    <div class="modal-body">
    
    <div class="form">

   <?php
   
         $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'users-create-form',
	'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'method'=>'post',
        'action'=>array("users/create"),
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
		

	<?php echo $form->errorSummary($model,'Opps!!!', null,array('class'=>'alert alert-error span12')); ?>
        		
   <div class="control-group">		
			<div class="span4">
			
							  <div class="row">
					  <?php echo $form->labelEx($model,'id'); ?>
					  <?php echo $form->textField($model,'id',array('size'=>10,'maxlength'=>10)); ?>
					  <?php echo $form->error($model,'id'); ?>
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
        'onclick' => "$('#users-create-modal').modal('hide')",
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
 
   var data=$("#users-create-form").serialize();
     


  jQuery.ajax({
   type: 'POST',
    url: '<?php
 echo Yii::app()->createAbsoluteUrl("users/create"); ?>',
   data:data,
success:function(data){
                //alert("succes:"+data); 
                if(data!="false")
                 {
                  $('#users-create-modal').modal('hide');
                  
                    $.fn.yiiGridView.update('users-grid', {
                     
                         });
                   
                 }
                 
              },
   error: function(data) { // if error occured
         alert("Error occured.please try again");
         alert(data);
    },

  dataType:'html'
  });

}

function renderCreateForm()
{
  $('#users-create-form').each (function(){
  this.reset();
   });

  
  $('#users-view-modal').modal('hide');
  
  $('#users-create-modal').modal({
   show:true,
   
  });
}

</script>
