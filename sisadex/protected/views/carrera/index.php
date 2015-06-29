<?php
$this->renderPartial("_ajax_update");
$this->renderPartial("_ajax_create_form", array("model" => $model));
$this->pageTitle = Yii::app()->name . ' - Carreras.';
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('carrera-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<div class="titulo">
    <h1>Carreras</h1>
</div>
<?php
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Nueva', 'icon' => 'icon-plus', 'url' => 'javascript:void(0);', 'linkOptions' => array('onclick' => 'renderCreateForm()')),
        array('label' => 'Listado', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Buscar', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
        array('label' => 'Exportar a PDF', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GeneratePdf'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
        array('label' => 'Exportar a Excel', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GenerateExcel'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
        array(
            'itemOptions' => array('id' => 'deleteAll',
                'onclick' => 'delete_all_records()'
            ),
            'label' => 'Eliminar todas las carreras',
            'icon' => 'icon-remove-sign',
            'url' => '#',
            'linkOptions' => array(
                'class' => ' btn-danger btn-small '
            ),
            'visible' => Yii::app()->user->isAdmin(),
        ),
    ),
));
?>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search', array(
        'model' => $model,
    )); ?>
</div><!-- search-form -->
<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'carrera-grid',
    'dataProvider' => $model->search(),
    'type' => $this->table_style,
    'template' => $this->table_template,
    'htmlOptions' => array('style' => 'width:750px'),
    'columns' => array(
        'id',
        'nombreCarrera',
        array(
            'type' => 'raw',
            'value' => '"
              <a href=\'javascript:void(0);\' onclick=\'renderUpdateForm(".$data->id.")\'   class=\'btn btn-small view\'  ><i class=\'icon-pencil\'></i></a>
              <a href=\'javascript:void(0);\' onclick=\'delete_record(".$data->id.")\'   class=\'btn btn-small view\'  ><i class=\'icon-trash\'></i></a>
             "',
            'htmlOptions' => array('style' => 'width:80px; text-align: center')
        ),
    ),
));
?>
<script type="text/javascript">
    function delete_record(id) {
        bootbox.confirm("<img src='"+baseUrl+"/images/warning.png'/>  ¿Está seguro de eliminar esta carrera?<br/><br/> <p class='text-warning'>Se eliminarán todos los exámenes asociados a ella.</p>", function (result) {
            if (!result) return;
            var data = "id=" + id;
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo Yii::app()->createAbsoluteUrl("carrera/delete"); ?>',
                data: data,
                success: function (data) {
                    if (data == "true") {
                        $('#carrera-view-modal').modal('hide');
                        $.fn.yiiGridView.update('carrera-grid', {
                        });
                    }
                    else
                        alert("deletion failed");
                },
                error: function (data) { // if error occured
                    alert(JSON.stringify(data));
                    bootbox.alert("Se ha producido un error interno. Contacte al administrador.");
                    //  alert(data);
                },
                dataType: 'html'
            });
        });
    }
    function delete_all_records() {
        bootbox.dialog({
            message: "<img src='"+baseUrl+"/images/warning.png'/>  Se eliminarán todos las carreras y los planes asociados a ellas.<br/> <br/>Por seguridad debe ingresar la contraseña de adminsitrador<br/><br/><input type='password' id ='pass' class='span3' ></input>",
            title: "Confirmar eliminación",
            buttons: {
                cancelar: {
                    label: "Cancelar",
                    className: "btn-default",
                    callback: function (pass) {
                    }
                },
                main: {
                    label: "Eliminar",
                    className: "btn-danger",
                    callback: function (pass) {
                        jQuery.ajax({
                            type: 'POST',
                            url: '<?php  echo Yii::app()->createAbsoluteUrl("carrera/deleteAll"); ?>',
                            data: {pass: $('#pass').val()},
                            success: function (data) {
                                console.log(data);
                                if (data == "true") {
                                    $.fn.yiiGridView.update('carrera-grid', {
                                    });
                                }
                                else
                                    bootbox.alert("Contraseña incorrecta.");
                            },
                            error: function (data) { // if error occured
                                //alert(JSON.stringify(data));
                                bootbox.alert("Se ha producido un error interno. Contacte al administrador.");
                                //  alert(data);
                            },
                            dataType: 'html'
                        });
                    }
                }
            }
        });
    }
</script>
 
