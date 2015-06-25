<div class="titulo">
    <h2>Modificar Plan</h2>
</div>
<?php
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Nuevo', 'visible' => Yii::app()->user->isadmin(), 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'Listado', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('label' => 'Modificar', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'active' => true, 'linkOptions' => array()),
    ),
));
?>
<?php echo $this->renderPartial('_formUpdate', array('model' => $model)); ?>