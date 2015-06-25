    <div id='materia-plan-update-modal' class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
   
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Update materia-plan #<?php echo $model->Materia_id; ?></h3>
    </div>
    
    <div class="modal-body">
 
    
    
    <div class="form">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'materia-plan-update-form',
	'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'method'=>'post',
        'action'=>array("materia-plan/update"),
	'type'=>'horizontal',
	'htmlOptions'=>array(
                               'onsubmit'=>"return false;",/* Disable normal form submit */
                               'onkeypress'=>" if(event.keyCode == 13){ update(); } " /* Do ajax call when user presses enter key */
                            ),               
	
)); ?>
     	<fieldset>
		<legend>
			<p class="note">Campos obligatorios <span class="required">*</span></p>
		</legend>

	<?php echo $form->errorSummary($model,'Opps!!!', null,array('class'=>'alert alert-error span12')); ?>
        		
   <div class="control-group">		
			<div class="span4">
			
			<?php echo $form->hiddenField($model,'Materia_id',array()); ?>
			
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
			//'id'=>'sub2',
			'type'=>'primary',
                        'icon'=>'ok white', 
			'label'=>$model->isNewRecord ? 'Crear' : 'Guardar',
			'htmlOptions'=>array('onclick'=>'update();'),
		));
		
		?>
             
	</div> 
   </div><!--end modal footer-->	
</fieldset>

<?php $this->endWidget(); ?>

</div>


</div><!--end modal-->



