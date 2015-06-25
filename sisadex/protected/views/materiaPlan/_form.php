<div class="form">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'materia-plan-form',
	'enableAjaxValidation'=>false,
        'method'=>'post',
	'type'=>'horizontal',
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data'
	)
)); ?>
     	<fieldset>
		<legend>
			<p class="note">Campos obligatorios <span class="required">*</span></p>
		</legend>

	<?php echo $form->errorSummary($model,'Opps!!!', null,array('class'=>'alert alert-error span12')); ?>
        		
   <div class="control-group">		
			<div class="span4">

	<?php echo $form->textFieldRow($model,'Plan_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'anio',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'cuatrimestre',array('class'=>'span5')); ?>

                        </div>   
  </div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
                        'icon'=>'ok white',  
			'label'=>$model->isNewRecord ? 'Crear' : 'Guardar',
		)); ?>
              <?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'reset',
                        'icon'=>'remove',  
			'label'=>'Limpiar',
		)); ?>
	</div>
</fieldset>

<?php $this->endWidget(); ?>

</div>
