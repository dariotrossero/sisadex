<?php
$this->pageTitle = Yii::app()->name . ' - Materias.';
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('materia-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<div class="titulo">
    <h1>Materias</h1>
</div>
<?php
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Nuevo', 'icon' => 'icon-plus', 'url' => 'javascript:void(0);', 'linkOptions' => array('onclick' => 'renderCreateForm()')),
        array('label' => 'Listado', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Buscar', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
        array('label' => 'Exportar a PDF', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GeneratePdf'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
        array('label' => 'Exportar a Excel', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GenerateExcel'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
        array(
            'itemOptions' => array('id' => 'deleteAll',
                'onclick' => 'delete_all_records()'
            ),
            'label' => 'Eliminar todos las materias',
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
    'id' => 'materia-grid',
    'dataProvider' => $model->searchSinDefault(),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'htmlOptions' => array('style' => 'width:750px'),
    'columns' => array(
        'id',
        'nombreMateria',
        array(
            'type' => 'raw',
            'value' => '"
		    <!--  <a href=\'javascript:void(0);\' onclick=\'renderView(".$data->id.")\'   class=\'btn btn-small view\'  ><i class=\'icon-eye-open\'></i></a> --!>
		      <a href=\'javascript:void(0);\' onclick=\'renderUpdateForm(".$data->id.")\'   class=\'btn btn-small view\'  ><i class=\'icon-pencil\'></i></a>
		      <a href=\'javascript:void(0);\' onclick=\'delete_record(".$data->id.")\'   class=\'btn btn-small view\'  ><i class=\'icon-trash\'></i></a>
		     "',
            'htmlOptions' => array('style' => 'width:80px; text-align: center')
        ),
    ),
));
$this->renderPartial("_ajax_update");
$this->renderPartial("_ajax_create_form", array("model" => $model));
?>
<script type="text/javascript">
    function delete_record(id) {
        var id;
        this.id = id;
        bootbox.confirm("<img src='"+baseUrl+"/images/warning.png'/> ¿Está seguro de eliminar esta materia?<br/><br/><p class='text-warning'> Se eliminará el usuario asociado y todos los exámenes de la misma.</p>", function (result) {
            if (!result) return;
            //  $('#ajaxtest-view-modal').modal('hide');
            var data = "id=" + id;
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo Yii::app()->createAbsoluteUrl("materia/delete"); ?>',
                data: data,
                success: function (data) {
                    if (data == "true") {
                        $.fn.yiiGridView.update('materia-grid', {
                        });
                    }
                    else
                        alert("deletion failed");
                },
                error: function (data) { // if error occured
                    //alert(JSON.stringify(data));
                    bootbox.alert("Se ha producido un error interno. Contacte al administrador.");
                    //  alert(data);
                },
                dataType: 'html'
            });
        });
    }
    function delete_all_records() {
        bootbox.dialog({
            message: "<img src='"+baseUrl+"/images/warning.png'/>  Se eliminarán todos las materias y los exámenes asociados a ellas.<br/> <br/>Por seguridad debe ingresar la contraseña de adminsitrador<br/><br/><input type='password' id ='pass' class='span3' ></input>",
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
                            url: '<?php  echo Yii::app()->createAbsoluteUrl("materia/deleteAll"); ?>',
                            data: {pass: $('#pass').val()},
                            success: function (data) {
                                console.log(data);
                                if (data == "true") {
                                    $.fn.yiiGridView.update('materia-grid', {
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
<style type="text/css" media="print">
    body {
        visibility: hidden;
    }
    .printableArea {
        visibility: visible;
    }
</style>
 
