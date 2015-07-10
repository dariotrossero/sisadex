<?php /* @var $this ExamenController */
/* @var $model Examen */
/* @var $form CActiveForm */
if (Yii::app()->user->isAdmin())
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/examenCreateAdmin.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/examenCreate.js', CClientScript::POS_END);
$this->renderPartial("_ajax_create_form");
?>
<div class="form">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array('id' => 'examen-form', 'enableAjaxValidation' => false, 'method' => 'post', 'type' => 'horizontal', 'htmlOptions' => array('enctype' => 'multipart/form-data'))); ?>
    <?php echo CHtml::hiddenField('cantExamenes', $this->cantExamenes, array('id' => 'cantExamenes')); ?>
    <div class="alert alert-warning" id="msjError" style="">Atención: Hay al menos un examen de otra materia del plan en
        esa misma fecha.
        </br><a onclick="showModal()" id="showExams">Mostrar examenes</a>
    </div>
    <p class="note">
        Campos obligatorios <span class="required">*</span>
        <a onclick="showAgenda()" id="showAgenda">Mostrar agenda</a>
    </p>

    <div class="control-group">
        <div class="span10">
            <div class="row">
                <div class="span4">
                    <?php if (Yii::app()->user->isAdmin()) {
                        echo $form->labelEx($modelos[1], 'materia_id');
                        echo CHtml::activeDropDownList($modelos[1], '[1]materia_id',
                            CHtml::listData(Materia::model()->getTodasLasMaterias('nombreMateria'), 'id', 'concatened'),
                            array('options' => array($modelos[1]->materia_id => array('selected' => true)), 'empty' => '-Por favor seleccione-'));
                        echo $form->error($modelos[1], 'materia_id');
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
                            <?php
                            if (Yii::app()->user->isAdmin())
                                $materia_id = $modelos[1]->materia_id;
                            else
                                $materia_id = Yii::app()->user->name;

                            echo CHtml::activeDropDownList($modelos[$i], "[$i]tipoexamen_id",
                                CHtml::listData(Tipoexamen::model()->getTiposExamenes($materia_id),
                                    'id', 'nombreTipoExamen') + array(-1 => 'Otro...'),
                                array('onChange' => 'javascript:fillTipoPersonalizado(this)'),
                                array('options' => array(
                                    $modelos[$i]->tipoexamen_id => array('selected' => true)), 'empty' => '-Por favor seleccione-')); ?>
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
                        'htmlOptions' => array('id' => 'newExam'),
                    )); ?>
                <?php $this->widget('bootstrap.widgets.TbButton',
                    array(
                        'buttonType' => 'button',
                        'type' => 'action',
                        'icon' => ' icon-minus-sign',
                        'size' => 'small',
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
    exams = {};


    <?php echo "var materia_id;" ?>


    $(document).ready(function () {
        var examenes = parseInt($('#cantExamenes').val());
        for (var i = 1; i <= examenes; i++) {
            $('#entry' + i.toString()).show();
            if (i > 1)  $('#removeExam').show();
        };
        <?php if (Yii::app()->user->isAdmin())
            echo "materia_id= $('#Examen_1_materia_id').val();" ;
            else  echo "materia_id= ".Yii::app()->user->name.";";?>
    });

    $("#examen-form").submit(function (event) {
        //Se habilita nuevamente el id de materia para que sea tomado en el form. Sino falla.
        $('#Examen_1_materia_id').removeAttr("disabled");
    });

    CheckExamenOnSameDay = function () {
        $('div.alert').slideUp('fast');
        var fechaExamen = $(this).val();
        <?php if (Yii::app()->user->isAdmin())
        echo "materia_id= $('#Examen_1_materia_id').val();" ;
        else  echo "materia_id= ".Yii::app()->user->name.";";?>
        var action = 'CheckExamenOnSameDay/fechaExamen/' + fechaExamen + '/materia_id/' + materia_id;
        $('#reportarerror').html("");
        console.log(materia_id + ":" + fechaExamen);
        $.ajax({
            type: "GET",
            data: "fechaExamen=" + fechaExamen + "&materia_id=" + materia_id,
            url: "<?php echo CController::createUrl('examen/CheckExamenOnSameDay');?>",
            success: function (respuesta) {
                console.log(respuesta);
                exams = respuesta;
                if (Object.keys(exams).length === 0) {
                    $('#msjError').slideUp('fast');
                }
                else {
                    $('#msjError').slideDown('fast');
                }
            }
        });
    };

    $('#Examen_1_materia_id').change(CheckExamenOnSameDay);

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


    function showAgenda() {
        <?php if (Yii::app()->user->isAdmin())
        echo "materia_id= $('#Examen_1_materia_id').val();" ;
        else  echo "materia_id= ".Yii::app()->user->name.";";?>
        $.ajax({
            type: "GET",
            data: "materia_id=" + materia_id,
            url: "<?php echo CController::createUrl('examen/GetAgenda');?>",
            success: function (respuesta) {
                if (materia_id === "") {
                    string = "<center><h3>Seleccione una materia primero.</h3></center>"
                    $.modal(string);
                }
                agenda = respuesta;
                if (Object.keys(agenda).length === 0) {
                    string = "<center><h3>Aún no se han cargado examenes de otras materias del mismo plan.</h3></center>"
                    $.modal(string);
                }
                else {
                    string = "<h4><ul>";
                    for (var key in agenda) {
                        var obj = agenda[key];
                        string = string + "<li>" + obj.nombreMateria + "</br><h5>" + obj.nombreTipoExamen + "</br>" + convertDate(obj.fechaExamen) + "</h5></li></br>";
                    }
                    string = string + "</ul><h4>";
                    $.modal(string);
                }
            }
        });

    };
</script>

