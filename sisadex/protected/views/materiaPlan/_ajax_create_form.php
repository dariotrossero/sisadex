
    <div id='materia-plan-create-modal' class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Create materia-plan</h3>
    </div>
    
    <div class="modal-body">
    
    <div class="form">

   <?php
   
         $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'materia-plan-create-form',
	'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'method'=>'post',
        'action'=>array("materia-plan/create"),
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
		<legend>
			<p class="note">Campos obligatorios <span class="required">*</span></p>
		</legend>

	<?php echo $form->errorSummary($model,'Opps!!!', null,array('class'=>'alert alert-error span12')); ?>
        		
   <div class="control-group">		
			<div class="span4">
			
							  <div class="row">
					  <?php echo $form->labelEx($model,'Plan_id'); ?>
					  <?php echo $form->textField($model,'Plan_id'); ?>
					  <?php echo $form->error($model,'Plan_id'); ?>
				  </div>

			  				  <div class="row">
					  <?php echo $form->labelEx($model,'anio'); ?>
					  <?php echo $form->textField($model,'anio'); ?>
					  <?php echo $form->error($model,'anio'); ?>
				  </div>

			  				  <div class="row">
					  <?php echo $form->labelEx($model,'cuatrimestre'); ?>
					  <?php echo $form->textField($model,'cuatrimestre'); ?>
					  <?php echo $form->error($model,'cuatrimestre'); ?>
				  </div>

			  
                        </div>   
  </div>

  </div><!--end modal body-->
  
  <div class="modal-footer">
	<div class="form-actions">

		<?php
		
		 $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
                        'icon'=>'ok white', 
			'label'=>$model->isNewRecord ? 'Crear' : 'Guardar',
			)
			
		);
		
		?>
              <?php
 $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'reset',
                        'icon'=>'remove',  
			'label'=>'Limpiar',
		)); ?>
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
 
   var data=$("#materia-plan-create-form").serialize();
     


  jQuery.ajax({
   type: 'POST',
    url: '<?php
 echo Yii::app()->createAbsoluteUrl("materia-plan/create"); ?>',
   data:data,
success:function(data){
                //alert("succes:"+data); 
                if(data!="false")
                 {
                  $('#materia-plan-create-modal').modal('hide');
                  renderView(data);
                    $.fn.yiiGridView.update('materia-plan-grid', {
                     
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
  $('#materia-plan-create-form').each (function(){
  this.reset();
   });

  
  $('#materia-plan-view-modal').modal('hide');
  
  $('#materia-plan-create-modal').modal({
   show:true,
   
  });
}

</script>
