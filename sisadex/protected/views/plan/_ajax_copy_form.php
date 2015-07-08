<div id='plan-copy-modal' class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Copiar plan</h3>
    </div>
    <div class="modal-body">
        <?php
        $yearNow = date("Y");
        $yearFrom = $yearNow - 20;
        $yearTo = $yearNow + 5;
        $arrYears = array();
        foreach (range($yearFrom, $yearTo) as $number) {
            $arrYears[$number] = $number;
        }
        $arrYears = array_reverse($arrYears, true);
        ?>
        <div class="form">
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'plan-form',
                'enableAjaxValidation' => false,
                'method' => 'post',
                'type' => 'horizontal',
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data'
                )
            )); ?>
            <div class="alert alert-error " id="msjError" style="">Ya existe un plan en el año seleccionado.
            </div>
            <?php echo $form->errorSummary($model, 'Por favor corrija los siguientes errores de ingreso:', null, array('class' => 'alert alert-error')); ?>
            <div class="control-group">
                <div class="span4">
                    <?php echo $form->label($model, 'anioPlan'); ?>
                    <?php echo $form->dropDownList($model, 'id', array('anioPlan' => '-Por favor seleccione-') + $arrYears, array('id' => 'anioPlan')); ?>
                </div>

            </div>

            <?php $this->endWidget(); ?>
        </div>
        <!--end modal body-->

        <div class="modal-footer">
            <div>
                <?php
                $this->widget('bootstrap.widgets.TbButton', array(
                    'icon' => 'remove',
                    'label' => 'Cerrar',
                    'htmlOptions' => array(
                        'onclick' => "$('#plan-copy-modal').modal('hide')",
                    )
                )); ?>
                <?php
                $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'submit',
                    'type' => 'primary',
                    'icon' => 'ok white',
                    'label' => 'Clonar',
                    'htmlOptions' => array('onclick' => 'copy()'),
                )); ?>
            </div>
        </div>
        <!--end modal footer-->
        </fieldset>

    </div>

</div><!--end modal-->

<script type="text/javascript">
    var idPlan;
    var carrera_id;

    $('#anioPlan').change(function () {
        $(".alert-error").slideUp('fast');
        var anioPlan = $('#anioPlan').val();  // el "value" de ese <option> seleccionado
        var action = 'index?r=plan/TestExistsPlan&anioPlan=' + anioPlan + '&Carrera_id=' + carrera_id;
        $('#reportarerror').html("");
        $.getJSON(action, function (respuesta) {
            if (respuesta == "true") {
                $('#msjError').slideDown('fast');
                $(":submit").attr("disabled", true);
            }
            else {
                $('#msjError').slideUp('fast');
                $(":submit").removeAttr("disabled");
            }
        }).error(function (e) {
            $('#reportarerror').html(e.responseText);
        });
    });

    function copy() {
        var data = $("#anioPlan").val();

        jQuery.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("plan/copy"); ?>',
            data: {
                plan_id: idPlan,
                new_year: data
            },
            success: function (data) {
                if (data != "false") {
                    $('#plan-copy-modal').modal('hide');
                    $.fn.yiiGridView.update('plan-grid', {});
                }
            },
            error: function (data) { // if error occured
                $('#plan-copy-modal').modal('hide');
                bootbox.alert("Se ha producido un error interno. Contacte al administrador.");
            },
            dataType: 'html'
        });
    }

    function renderCopyForm(id) {
        idPlan = id.toString();
        carrera_id = idPlan.substring(4)
        $('#anioPlan').prop('selectedIndex',0);
        $('#msjError').hide();
        $('#plan-copy-form').each(function () {
            this.reset();
        });
        $('#plan-view-modal').modal('hide');
        $('#plan-copy-modal').modal({
            show: true
        });
    }
</script>
