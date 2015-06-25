<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Cambiar contraseña.';
?>

<div class="titulo">
    <h2>Cambiar contraseña</h2>
</div>

<?php 

$this->widget('bootstrap.widgets.TbMenu', array(
	'type'=>'pills',
	'items'=>array(
		
       array('label'=>'Listado', 'icon'=>'icon-th-list', 'url'=>Yii::app()->controller->createUrl('index'),'linkOptions'=>array()),
       
		// array('label'=>'Exportar a PDF', 'icon'=>'icon-download', 'url'=>Yii::app()->controller->createUrl('GeneratePdf'), 'linkOptions'=>array('target'=>'_blank'), 'visible'=>true),
		// array('label'=>'Exportar a Excel', 'icon'=>'icon-download', 'url'=>Yii::app()->controller->createUrl('GenerateExcel'), 'linkOptions'=>array('target'=>'_blank'), 'visible'=>true),
       array('label'=>'Cambiar contraseña', 'icon'=>'icon-lock', 'url'=>Yii::app()->controller->createUrl('changePassword'),'active'=>true, 'linkOptions'=>array())

       ),
    ));

    ?>
    <?php /** @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'changePassword-form',
        'inlineErrors'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
          'validateOnSubmit'=>true,
          ),
        'htmlOptions'=>array('class'=>'well'),
        )); ?>


        <?php echo $form->passwordFieldRow($model, 'currentPassword', array('class'=>'span3')); ?>
        <?php echo $form->passwordFieldRow($model, 'newPassword', array('class'=>'span3')); ?>
        <?php echo $form->passwordFieldRow($model, 'newPassword_repeat', array('class'=>'span3')); ?>
    </br>
    <?php $this->widget('bootstrap.widgets.TbButton',
    array('buttonType'=>'submit', 'label'=>'Enviar', 'type'=>'primary')); ?>
    <?php $this->endWidget(); ?>
