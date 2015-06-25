<?php /* @var $this Controller */ ?>

<?php
$baseUrl = Yii::app ()->baseUrl;
$cs = Yii::app ()->getClientScript ();
$cs->registerScriptFile ( $baseUrl . '/js/bootbox.js' );

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="en" />
	
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/styles.css'); ?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<?php Yii::app()->bootstrap->register(); ?>
</head>
<style type="text/css">
h1,h2,h3,h4 {
	font-family: 'Alegreya Sans SC', 'Arial', serif;
}

body {
	background-color: #354C75;
}
</style>
<body>

<?php

$this->widget ( 'bootstrap.widgets.TbNavbar', array (
		'items' => array (
				array (
						'class' => 'bootstrap.widgets.TbMenu',
						'items' => array (
								array (
										'label' => 'Inicio',
										'icon' => 'icon-home',
										'url' => array (
												'/site/index' 
										),
										'visible' => ! Yii::app ()->user->isadmin () 
								),
								
								array (
										'label' => 'Metricas',
										'icon' => 'icon-signal',
										'url' => array (
												'/metrica/calendar' 
										) 
								),
								array (
										'label' => 'Materias',
										'icon' => 'icon-book',
										'url' => array (
												'/materia/index' 
										),
										'visible' => Yii::app ()->user->isadmin () 
								),
								array (
										'label' => 'Carreras',
										'icon' => 'icon-folder-open',
										'url' => array (
												'/carrera/index' 
										),
										'visible' => Yii::app ()->user->isadmin () 
								),
								array (
										'label' => 'Planes',
										'icon' => 'icon-briefcase',
										'url' => array (
												'/plan/index' 
										),
										'visible' => ! Yii::app ()->user->isGuest 
								),
								array (
										'label' => 'Tipos de examenes',
										'icon' => 'icon-tags',
										'url' => array (
												'/tipoexamen/index' 
										),
										'visible' => ! Yii::app ()->user->isGuest 
								),
								array (
										'label' => 'Examenes',
										'icon' => 'icon-pencil',
										'url' => array (
												'/examen/index' 
										),
										'visible' => ! Yii::app ()->user->isGuest 
								),
								array (
										'label' => 'Usuarios',
										'icon' => 'user',
										'url' => array (
												'/users/index' 
										),
										'visible' => Yii::app ()->user->isAdmin () 
								) 
						// array('label'=>'Config.', 'icon'=>'icon-cog','url'=>array('/carrera/index'),'visible'=>Yii::app()->user->isadmin()),
												) 
				)
				,
				array (
						'class' => 'bootstrap.widgets.TbMenu',
						'htmlOptions' => array (
								'class' => 'pull-right' 
						),
						'items' => array (
								array (
										'label' => 'Iniciar Sesion',
										'url' => array (
												'/site/login' 
										),
										'icon' => 'icon-off',
										'visible' => Yii::app ()->user->isGuest 
								),
								array (
										'label' => 'Salir (' . Yii::app ()->user->name . ')',
										'icon' => 'icon-off',
										'url' => array (
												'/site/logout' 
										),
										'visible' => ! Yii::app ()->user->isGuest 
								) 
						) 
				) 
		) 
)
 );
?>

<div class="container" id="page">

		<div id="contenido">

	<?php echo $content; ?>

	</div>


	</div>
	<!-- page -->

	<div id="footer">

		<div id="browsers">
			Recomendamos <a href="https://www.google.com/chrome"
				style="margin-right: 5px" target="_blank"> <img
				src="images/googleChrome.png">
			</a> <a href="http://www.mozilla.org/firefox/" target="_blank"> <img
				src="images/mozillaFirefox.png">
			</a>

		</div>
		<div id="logo"></div>
	</div>

</body>
</html>

