<div class="form">
    <?php
    $this->renderPartial("_ajax_create_form_update");
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array('id' => 'examen-form', 'enableAjaxValidation' => false, 'method' => 'post', 'type' => 'horizontal', 'htmlOptions' => array('enctype' => 'multipart/form-data'))); ?>
    <div class="alert alert-warning" id="msjError" style="">Atención: Hay al menos un examen de otra materia del plan en esa misma fecha.
       </br><a onclick="showModal()" id="showExams">Mostrar examenes</a>
    </div>
    <p class="note">
        Campos obligatorios <span class="required">*</span>
        <a onclick="showAgenda()" id="showAgenda">Mostrar agenda</a>
    </p>
    <?php echo $form->errorSummary($model, '', null, array('class' => 'alert alert-error')); ?>
    <div class="control-group">
        <div class="span10">
            <div class="row">
                <?php
                if (Yii::app()->user->isAdmin()) {
                    echo CHtml::activeLabel($model, $model->materia->nombreMateria, array('class' => 'lead'));
                    echo $form->hiddenField($model, 'materia_id');
                }
                ?>
            </div>
            <div id="left-content">
                <div class="row">
                    <?php echo $form->labelEx($model, 'fechaExamen'); ?>
                    <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array('model' => $model,
                        'attribute' => 'fechaExamen',
                        'language' => 'es',
                        'themeUrl' => Yii::app()->baseUrl . '/css/jquery-ui-themes/themes',
                        'theme' => 'bootstrap', //try 'bee' also to see the changes
                        'flat' => true,
                        'options' => array(
                            'onSelect' => 'js: CheckExamenOnSameDay',
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
                    <?php echo $form->error($model, 'fechaExamen'); ?>
                </div>
            </div>
            <div id="right-content">
                <div class="row">
                    <?php echo $form->labelEx($model, 'tipoexamen_id'); ?>
                    <?php echo CHtml::dropDownList('Examen[tipoexamen_id]', $model->tipoexamen_id, CHtml::listData(Tipoexamen::model()->getTiposExamenes($model->materia_id), 'id', 'nombreTipoExamen') + array(-1 => 'Otro...'), array('id' => 'Examen_tipoexamen_id')); ?>
                    <?php echo $form->error($model, 'tipoexamen_id'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'descripcionExamen'); ?>
                    <?php echo $form->textArea($model, 'descripcionExamen', array('class' => 'span3', 'rows' => 5)); ?>
                    <?php echo $form->error($model, 'descripcionExamen'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'diasPreparacion'); ?>
                    <?php echo $form->textField($model, 'diasPreparacion', array('size' => 2, 'maxlength' => 2, 'class' => 'span1')); ?>
                    <?php echo $form->error($model, 'diasPreparacion'); ?>
                </div>
            </div>
            <div class="ActionButtons">
                <?php $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'submit',
                    'type' => 'primary',
                    'icon' => 'ok white',
                    'label' => $model->isNewRecord ? 'Crear' : 'Guardar',
                )); ?>
            </div>
        </div>
        <!-- Span4 -->
        <?php $this->endWidget(); ?>
    </div>
    <!-- Control-group -->
</div>
<!-- Form -->
<script type="text/javascript">
    <?php if (Yii::app()->user->isAdmin())
       echo "var materia_id = $('#Examen_materia_id').val()";
       else
       echo "var materia_id= ".Yii::app()->user->name.";";?>

    $('#Examen_tipoexamen_id').change(function () {
        $('div.alert').slideUp('fast');
        var opcionSeleccionada = $(this);
        var codigoExamen = opcionSeleccionada.val();        // el "value" de ese <option> seleccionado
        console.log(codigoExamen);
        if (codigoExamen == -1) {
            $('#tipoexamen-create-form').each(function () {
                this.reset();
            });
            $('#tipoexamen-view-modal').modal('hide');
            $('#tipoexamen-create-modal').modal({
                show: true
            });
        }
    });
    CheckExamenOnSameDay = function () {
        $('div.alert').slideUp('fast');
        var fechaExamen = $('#Examen_fechaExamen').val();  // el "value" de ese <option> seleccionado
        <?php if (Yii::app()->user->isAdmin())  
        echo "var materia_id= $('#Examen_materia_id').val();" ;
        else  echo "var materia_id= ".Yii::app()->user->name.";";?>
        var action = 'CheckExamenOnSameDay/fechaExamen/' + fechaExamen + '/materia_id/' + materia_id;
        $('#reportarerror').html("");
        $.ajax({
            type: "GET",
            data: "fechaExamen=" + fechaExamen + "&materia_id=" + materia_id,
            url: "<?php echo CController::createUrl('examen/CheckExamenOnSameDay');?>",
            success: function (respuesta) {
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
    $('#Examen_materia_id').change(CheckExamenOnSameDay);

    function showAgenda() {

        $.ajax({
            type: "GET",
            data: "materia_id=" + materia_id,
            url: "<?php echo CController::createUrl('examen/GetAgenda');?>",
            success: function (respuesta) {
                if (materia_id === "")  {
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

