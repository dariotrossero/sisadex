<?php  $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'search-materia-plan-form',
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
));  ?>


	<?php echo $form->textFieldRow($model,'Materia_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'Plan_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'anio',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'cuatrimestre',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'icon'=>'search white', 'label'=>'Buscar')); ?>
               <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'button', 'icon'=>'icon-remove-sign white', 'label'=>'Limpiar')); ?>
	</div>

<?php $this->endWidget(); ?>


<?php $cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/bootstrap/jquery-ui.css');
?>	
   <script>
	$(".btnreset").click(function(){
		$(":input","#search-materia-plan-form").each(function() {
		var type = this.type;
		var tag = this.tagName.toLowerCase(); // normalize case
		if (type == "text" || type == "password" || tag == "textarea") this.value = "";
		else if (type == "checkbox" || type == "radio") this.checked = false;
		else if (tag == "select") this.selectedIndex = "";
	  });
	});
   </script>

