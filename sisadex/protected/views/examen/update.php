<div class="titulo">
    <h2>Modificar Examen</h2>
</div>
<?php
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Nuevo', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'Listado', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('label' => 'Modificar', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'active' => true, 'linkOptions' => array()),
    ),
));
?>
<?php
if (!Yii::app()->user->isAdmin() && $model->materia_id != Yii::app()->user->name)
    throw new CHttpException('No tiene permisos para editar este examen');
?>
<?php echo $this->renderPartial('_formUpdate', array('model' => $model)); ?>