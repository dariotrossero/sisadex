<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;?>

<?php $this->widget('bootstrap.widgets.TbAlert', array(
    'block' => true,
    'fade' => true,
    'closeText' => '&times;', // false equals no close link
    'events' => array(),
    'htmlOptions' => array('class'=>'browserError'),
   
    
)); ?>

