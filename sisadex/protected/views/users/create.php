<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Create',
);

?>

<h1>Create Users</h1>
<hr/>
<?php 

$this->widget('bootstrap.widgets.TbMenu', array(
	'type'=>'pills',
	'items'=>array(
		array('label'=>'Nuevo', 'icon'=>'icon-plus', 'url'=>Yii::app()->controller->createUrl('create'),'active'=>true, 'linkOptions'=>array()),
                array('label'=>'Listado', 'icon'=>'icon-th-list', 'url'=>Yii::app()->controller->createUrl('index'), 'linkOptions'=>array()),
		array('label'=>'Buscar', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
	),
));

?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>