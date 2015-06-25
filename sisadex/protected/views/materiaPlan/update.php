<?php
$this->breadcrumbs=array(
	'Materia Plans'=>array('index'),
	$model->Materia_id=>array('view','id'=>$model->Materia_id),
	'Update',
);

?>

<h1>Update MateriaPlan <?php echo $model->Materia_id; ?></h1>
<hr/>

<?php 

$this->widget('bootstrap.widgets.TbMenu', array(
	'type'=>'pills',
	'items'=>array(
		array('label'=>'Nuevo', 'icon'=>'icon-plus', 'url'=>Yii::app()->controller->createUrl('create'), 'linkOptions'=>array()),
                array('label'=>'Listar', 'icon'=>'icon-th-list', 'url'=>Yii::app()->controller->createUrl('index'), 'linkOptions'=>array()),
                array('label'=>'Actualizar', 'icon'=>'icon-edit', 'url'=>Yii::app()->controller->createUrl('update',array('id'=>$model->Materia_id)),'active'=>true, 'linkOptions'=>array()),
	),
));

?>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>