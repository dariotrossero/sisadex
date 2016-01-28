<head>
    <?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/d3.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/cal-heatmap.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/moment-with-langs.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/underscore-min.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/basic/jquery.simplemodal.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/basic/basic.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/underscore-min.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/metricas-common.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/metricas-calendar.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/modal/basic.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/cal-heatmap.css');
    $browser = new EWebBrowser();
    ?>
</head>

<body>

<div class="titulo">
    <h1>Calendario</h1>
</div>


<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'tabs', // '', 'tabs', 'pills' (or 'list')
    'stacked' => false, // whether this is a stacked menu
    'items' => array(
        array('label' => 'Calendario', 'url' => '#', 'active' => true),
        array('label' => 'Linea de tiempo', 'url' => array('/metrica/timeline')),
        array('label' => 'Evolución', 'url' => array('/metrica/evolution')),

    ),
)); ?>

<div id="buttons">

<div class="anios-calendar">
<?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'primary',
    'toggle' => 'checkbox', // 'checkbox' or 'radio'
    'buttons' => array(
        array('label'=>'1er año','htmlOptions' => array('id' => 'anio_1','onclick'=>'clickYear(1)')),
    ),
)); ?>

<?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'primary',
    'toggle' => 'checkbox', // 'checkbox' or 'radio'
    'buttons' => array(
        array('label'=>'2do año','htmlOptions' => array('id' => 'anio_2','onclick'=>'clickYear(2)')),
    ),
)); ?>

<?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'primary',
    'toggle' => 'checkbox', // 'checkbox' or 'radio'
    'buttons' => array(
        array('label'=>'3er año','htmlOptions' => array('id' => 'anio_3','onclick'=>'clickYear(3)')),
    ),
)); ?>

<?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'primary',
    'toggle' => 'checkbox', // 'checkbox' or 'radio'
    'buttons' => array(
        array('label'=>'4to año','htmlOptions' => array('id' => 'anio_4','onclick'=>'clickYear(4)')),
    ),
)); ?>
<?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'primary',
    'toggle' => 'checkbox', // 'checkbox' or 'radio'
    'buttons' => array(
        array('label'=>'5to año','htmlOptions' => array('id' => 'anio_5','onclick'=>'clickYear(5)')),
    ),
)); ?>
</div>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'action',

        'icon' => ' icon-remove-sign',
        'label' => 'Limpiar',
        'htmlOptions' => array('onclick' => 'reset()'),
    )); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'action',

        'icon' => 'icon-arrow-left',

        'htmlOptions' => array('onclick' => 'go2prev()'),
    )); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'action',

        'icon' => 'icon-arrow-right',

        'htmlOptions' => array('onclick' => 'go2next()'),
    )); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'action',

        'label' => '1° Cuat.',
        'htmlOptions' => array('onclick' => 'go2FirstCuat()'),
    )); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'action',

        'label' => '2° Cuat.',
        'htmlOptions' => array('onclick' => 'go2SecondCuat()'),
    )); ?>

<?php 
$yearNow = date("Y");
$yearFrom = $yearNow - 5;
$arrYears = array();
foreach (range($yearFrom, $yearNow) as $number) {
    $arrYears[$number] = $number;
}
$arrYears = array_reverse($arrYears, true);?>

<div>
<?php echo CHtml::dropDownList('yearList', $yearNow, $arrYears); ?>
</div>
    <div id="wait_animation">
        <div class="circle"></div>
        <div class="circle1"></div>
    </div>
</div>

<div id="planes-metricas" class="drop" ondrop="dropElement(this, event)" ondragenter="return false"
     ondragover="return false">

    <?php
    $criteria = new CDbCriteria();
    $criteria->order = 'anioPlan';
    $planes = Plan::model()->findAll($criteria);
    echo '<ul class="lista">';
    foreach ($planes as $item) {
        $codigo = $item->id;
        $anio = $item->anioPlan;
        $nombre = $item->carrera->nombreCarrera;
        if ($browser->getBrowser() == "Internet Explorer")
            echo '<a href="#" onclick="return false;" draggable="true" class="plan" id="' . $codigo . '" ondragstart="dragElement(this, event,0)">' . $anio . ' - ' . $nombre . '</a>';
        else
            echo '<span draggable="true" class="plan" id="' . $codigo . '" ondragstart="dragElement(this, event,0)">' . $anio . ' - ' . $nombre . '</span>';
    }
    echo '</ul>';
    ?>

</div>
<div id="target" class="drop " ondrop="dropElement(this, event)"
     ondragover="allowDrop(event)">
    Arrastra los elementos al calendario
</div>

<div id="materias-metricas" class="drop" ondrop="dropElement(this, event)" ondragenter="return false"
     ondragover="return false">

    <!-- Generacion de las materias dropeables -->
    <?php
    $materias = Materia::model()->getTodasLasMaterias('nombreMateria');
    echo '<ul class="lista">';
    foreach ($materias as $item) {
        $codigo = $item->id;
        $nombre = $item->nombreMateria;
        if ($browser->getBrowser() == "Internet Explorer")
            echo '<a href="#" onclick="return false;" draggable="true" class="materia" id="' . $codigo . '" ondragstart="dragElement(this, event,1)">' . $nombre . ' - ' . $codigo . '</a>';
        else
            echo '<span draggable="true" class="materia" id="' . $codigo . '" ondragstart="dragElement(this, event,1)">' . $nombre . ' - ' . $codigo . '</span>';
    }

    echo '</ul>';
    ?>
    <!-- FIN Generacion de las materias dropeables -->


</div>

</body>

<style type="text/css">
#planes-metricas {
        margin-top: 10px;
    }

#materias-metricas {
    margin-top: 10px;
}
</style>
