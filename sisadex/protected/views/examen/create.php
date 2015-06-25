<div class="titulo">
    <h2>Nuevo Examen</h2>
</div>
<?php
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Nuevo', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Listado', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),

    ),
));
?>
<?php echo $this->renderPartial('_form', array('model' => $model, 'modelos' => $modelos)); ?>