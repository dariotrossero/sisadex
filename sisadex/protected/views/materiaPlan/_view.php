<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('Materia_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->Materia_id),array('view','id'=>$data->Materia_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Plan_id')); ?>:</b>
	<?php echo CHtml::encode($data->Plan_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('anio')); ?>:</b>
	<?php echo CHtml::encode($data->anio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cuatrimestre')); ?>:</b>
	<?php echo CHtml::encode($data->cuatrimestre); ?>
	<br />


</div>