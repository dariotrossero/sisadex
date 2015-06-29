<?php /* @var $this ExamenController */
/* @var $model Examen */
/* @var $form CActiveForm */
if (Yii::app()->user->isAdmin())
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/examenCreate.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/examenCreate2.js', CClientScript::POS_END);
?>
<div class="form">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array('id' => 'examen-form', 'enableAjaxValidation' => false, 'method' => 'post', 'type' => 'horizontal', 'htmlOptions' => array('enctype' => 'multipart/form-data'))); ?>
    <?php echo CHtml::hiddenField('cantExamenes', $this->cantExamenes, array('id' => 'cantExamenes')); ?>
    <div class="alert alert-warning span12" id="msjError" style="">Atención: Hay un examen en esa fecha de otra materia
        del plan.
    </div>
    <p class="note">
        Campos obligatorios <span class="required">*</span>
    </p>
    <div class="control-group">
        <div class="span10">
            <div class="row">
                <div class="span4">
                    <?php if (Yii::app()->user->isAdmin()) {
                        ?>
                        <?php echo $form->labelEx($modelos[1], 'materia_id'); ?>
                        <?php
                        echo CHtml::activeDropDownList($modelos[1], '[1]materia_id',
                            CHtml::listData(Materia::model()->getTodasLasMaterias('nombreMateria'), 'id', 'concatened'),
                            array('options' => array($modelos[1]->materia_id => array('selected' => true)), 'empty' => '-Por favor seleccione-')); ?>
                        <?php echo $form->error($modelos[1], 'materia_id');
                    }
                    ?>
                </div>
            </div>
            <?php for ($i = 1; $i <= 10; $i++) : ?>
                <div class="entries" id="<?php echo 'entry' . $i ?>">
                    <hr/>
                    <div id="left-content">
                        <div class="row">
                            <?php echo $form->labelEx($modelos[$i], 'fechaExamen'); ?>
                            <?php $this->widget('zii.widgets.jui.CJuiDatePicker',
                                array('model' => $modelos[$i],
                                    'attribute' => "[$i]fechaExamen",
                                    'language' => 'es',
                                    'name' => "[$i]fechaExamen",
                                    'themeUrl' => Yii::app()->baseUrl . '/css/jquery-ui-themes/themes',
                                    'theme' => 'bootstrap', //try 'bee' also to see the changes
                                    'flat' => false,
                                    'options' => array(
                                        'onSelect' => 'js: CheckExamenOnSameDay',
                                        //'showOn' => 'both',             // also opens with a button
                                        'dateFormat' => 'dd-mm-yy', // format of "2012-12-25"
                                        'showOtherMonths' => true, // show dates in other months
                                        'selectOtherMonths' => true, // can seelect dates in other months
                                        'changeYear' => true, // can change year
                                        'changeMonth' => true, // can change month
                                        'showButtonPanel' => true, 'yearRange' => '2013:2099', // range of year
                                    ),
                                    'htmlOptions' => array('size' => '7',
                                        'readonly' => "readonly",
                                        'maxlength' => '10', // textField maxlength
                                    ),
                                ));
                            ?>
                        </div>
                    </div>
                    <div id="right-content">
                        <div class="row">
                            <?php echo $form->error($modelos[$i], 'tipoexamen_id'); ?>
                            <?php echo $form->labelEx($modelos[$i], "[$i]tipoexamen_id"); ?>
                            <?php echo CHtml::activeDropDownList($modelos[$i], "[$i]tipoexamen_id",
                                CHtml::listData(Tipoexamen::model()->getTiposExamenes(Yii::app()->user->name),
                                    'id', 'nombreTipoExamen') + array(-1 => 'otro'),
                                array('onChange' => 'javascript:fillTipoPersonalizado(this)'),
                                array('options' => array(
                                    $modelos[$i]->tipoexamen_id => array('selected' => true)), 'empty' => '-Por favor seleccione-')); ?>
                        </div>
                        <div <?php echo 'class="row tipoPersonalizado" id="tipoPersonalizado_' . $i . '"' ?> >
                            <?php echo $form->labelEx($modelos[$i], 'TipoExamenPersonalizado'); ?>
                            <?php echo $form->textField($modelos[$i], "[$i]TipoExamenPersonalizado", array('size' => 45, 'maxlength' => 60)); ?>
                            <?php echo $form->error($modelos[$i], 'TipoExamenPersonalizado'); ?>
                        </div>
                        <div id="row">
                            <?php echo $form->labelEx($modelos[$i], 'descripcionExamen'); ?>
                            <?php echo $form->textArea($modelos[$i], "[$i]descripcionExamen", array('class' => 'span3', 'rows' => 5)); ?>
                            <?php echo $form->error($modelos[$i], 'descripcionExamen'); ?>
                        </div>
                        <div class="row">
                            <?php echo $form->labelEx($modelos[$i], 'diasPreparacion'); ?>
                            <?php echo $form->textField($modelos[$i], "[$i]diasPreparacion", array('size' => 2, 'maxlength' => 2, 'class' => 'span1')); ?>
                            <?php echo $form->error($modelos[$i], 'diasPreparacion'); ?>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
            <div class="ActionButtons">
                <?php $this->widget('bootstrap.widgets.TbButton',
                    array(
                        'buttonType' => 'button',
                        'type' => 'action',
                        'icon' => ' icon-plus-sign',
                        'size' => 'small',
                        // 'label' =>  'Agregar examen',
                        'htmlOptions' => array('id' => 'newExam'),
                    )); ?>
                <?php $this->widget('bootstrap.widgets.TbButton',
                    array(
                        'buttonType' => 'button',
                        'type' => 'action',
                        'icon' => ' icon-minus-sign',
                        'size' => 'small',
                        //   'label' =>  'Eliminar examen',
                        'htmlOptions' => array('id' => 'removeExam'),
                    )); ?>
                <br/>
                <br/>
                <?php $this->widget('bootstrap.widgets.TbButton',
                    array(
                        'buttonType' => 'submit',
                        'type' => 'primary',
                        'icon' => 'ok white',
                        'label' => 'Guardar',
                    )); ?>
            </div>
        </div>
        <!-- Span4 -->
        <?php $this->endWidget(); ?>
    </div>
    <!-- Control-group -->
</div>
<!-- Form -->
<script>
    CheckExamenOnSameDay = function () {
        $('div.alert').slideUp('fast');
        var fechaExamen = $(this).val();
        <?php if (Yii::app()->user->isAdmin())
        echo "var materia_id= $('#Examen_1_materia_id').val();" ;
        else  echo "var materia_id= ".Yii::app()->user->name.";";?>
        var action = 'CheckExamenOnSameDay/fechaExamen/' + fechaExamen + '/materia_id/' + materia_id;
        $('#reportarerror').html("");
        $.ajax({
       type: "GET",      
       data: "fechaExamen="+fechaExamen+"&materia_id="+materia_id,
       url: "<?php echo CController::createUrl('examen/CheckExamenOnSameDay');?>",
       success: function (respuesta){
        console.log(respuesta);
       if (respuesta == "true") {
                $('#msjError').slideDown('fast');
            }
            else {
                $('#msjError').slideUp('fast');
            }  }   
    });  
    };
    $('#Examen_materia_id').change(CheckExamenOnSameDay);
    $('#newExam').click(function () {
        var examenes = parseInt($('#cantExamenes').val());
        if (examenes < 9) {
            $('#cantExamenes').val(examenes + 1);
            $('#entry' + (examenes + 1).toString()).slideDown(300);
            $('#removeExam').show();
        }
        if (examenes == 9) {
            $('#cantExamenes').val(examenes + 1);
            $('#entry' + (examenes + 1).toString()).slideDown(300);
            $('#removeExam').show();
            $('#newExam').hide();
        }
    });
    $('#removeExam').click(function () {
        var examenes = parseInt($('#cantExamenes').val());
        resetFields(examenes);
        if (examenes <= 10 && examenes > 2) {
            $('#entry' + examenes.toString()).slideUp(300);
            $('#cantExamenes').val(examenes - 1);
            $('#newExam').show();
        }
        if (examenes == 2) {
            $('#entry' + examenes.toString()).slideUp(300);
            $('#cantExamenes').val(examenes - 1);
            $('#newExam').show();
            $('#removeExam').hide();
        }
        function resetFields(examenes) {
            $('#_' + examenes.toString() + 'fechaExamen').val("");
            $('#Examen_' + examenes.toString() + '_tipoexamen_id option:first').attr("selected", true);
            $('#Examen_' + examenes.toString() + '_diasPreparacion').val("0");
            $('#Examen_' + examenes.toString() + '_descripcionExamen').val("");
            $('#Examen_' + examenes.toString() + '_TipoExamenPersonalizado').val("");
            $('#tipoPersonalizado_' + examenes.toString()).hide();
        }
    });
    $(document).ready(function () {
        var examenes = parseInt($('#cantExamenes').val());
        for (var i = 1; i <= examenes; i++) {
            $('#entry' + i.toString()).show();
            if (i > 1)  $('#removeExam').show();
        }
        ;
    });
</script>
