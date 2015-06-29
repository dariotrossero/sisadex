    <div id='materia-update-modal' class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
   
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

    <h3>Modificar materia</h3>

    </div>
    
    <div class="modal-body">
 
    
    
    <div class="form">
<?php 
$b = new EWebBrowser();
if ($b->browser=="Firefox") {
$options = array(
                               'onsubmit'=>"return false;",/* Disable normal form submit */
                               'onkeypress'=>" if(event.keyCode == 13){ update(); } " /* Do ajax call when user presses enter */
);
}
else {
	$options = array(
                               'onsubmit'=>"return false;",/* Disable normal form submit */
                            );
}


$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'materia-update-form',
	'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'method'=>'post',
        'action'=>array("materia/update"),
	'type'=>'horizontal',
	'htmlOptions'=> $options,               
	
)); ?>
     	<fieldset>
		
			<p class="note">Campos obligatorios <span class="required">*</span></p>
		

	<?php echo $form->errorSummary($model,null, null,array('class'=>'alert alert-error span12')); ?>
        		
   <div class="control-group">		
			<div class="span4">
			
			<?php echo $form->hiddenField($model,'id',array()); ?>
			
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
        'onclick' => "$('#materia-update-modal').modal('hide')",
        )  
		)); ?>

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



