<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('anioPlan')); ?>:</b>
	<?php echo CHtml::encode($data->anioPlan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Carrera_id')); ?>:</b>
	<?php echo CHtml::encode($data->Carrera_id); ?>
	<br />


</div>