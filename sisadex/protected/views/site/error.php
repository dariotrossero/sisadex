<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';

?>


<div id="error">
<h2>Error <?php echo $code; ?></h2>
</div>


<div class="error">
<?php echo CHtml::encode($message); 
echo "<br/>";
echo "<br/>";


$this->widget('bootstrap.widgets.TbMenu', array(
	'type'=>'pills',
	'items'=>array(
		
    array('label'=>'Volver', 'icon'=>'icon-hand-left', 'url'=>(Yii::app()->request->urlReferrer!=null) ? Yii::app()->request->urlReferrer : Yii::app()->baseUrl,'active'=>true, 'linkOptions'=>array())

	),
));

?>
</div>
