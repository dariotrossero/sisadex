<head>
<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/basic/jquery.simplemodal.js'); 
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/modal/basic.css');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/examenCommon.js');
?>
</head>
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
