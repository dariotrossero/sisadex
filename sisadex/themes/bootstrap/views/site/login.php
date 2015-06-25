	<?php $this->widget('bootstrap.widgets.TbAlert', array(
    'block' => true,
    'fade' => true,
    'closeText' => '&times;', // false equals no close link
    'events' => array(),
    'htmlOptions' => array('class'=>'browserError'),
   
    
)); ?>
<div id="loginBox">
<hr/>
<h1>Iniciar Sesión</h1>
<hr/>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'login-form',
    'type'=>'vertical',
	'enableClientValidation'=>true,
	 'htmlOptions'=>array('class'=>'well'),
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	
)); ?>



	<?php echo $form->textFieldRow($model, 'username', array('class'=>'input-medium span2','labelOptions' => array('label' => false),'placeholder'=>'Usuario' , 'prepend'=>'<i class="icon-user"></i>')); ?>

	<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'input-medium span2','placeholder'=>'Contraseña' ,'labelOptions' => array('label' => false), 'prepend'=>'<i class="icon-lock"></i>')); ?>
	
	

	<?php echo $form->checkBoxRow($model,'rememberMe'); ?>


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'submit',
             'htmlOptions'   => array('id'=> 'loginButton'),
            'type'=>'primary',
            'label'=>'Entrar',
        )); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

</div>

