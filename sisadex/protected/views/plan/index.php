<?php
$this->pageTitle = Yii::app()->name . ' - Planes.';
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('plan-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<div class="titulo">
    <h1>Planes</h1>
</div>
<?php
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Nuevo', 'visible' => Yii::app()->user->isadmin(), 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create')),
        array('label' => 'Listado', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Buscar', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
        array('label' => 'Exportar a PDF', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GeneratePdf'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
        array('label' => 'Exportar a Excel', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GenerateExcel'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
        array(
            'itemOptions' => array('id' => 'deleteAll',
                'onclick' => 'delete_all_records()'
            ),
            'label' => 'Eliminar todos los planes',
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
    'id' => 'plan-grid',
    'dataProvider' => $model->search(),
    'type' => $this->table_style,
    'template' => $this->table_template,
    'htmlOptions' => array('style' => 'width:750px'),
    'columns' => array(
        'anioPlan',
        array(
            'header' => 'Carrera',
            'name' => 'Carrera_id',
            'value' => '$data->carrera->nombreCarrera',
        ),
        array(
            'type' => 'raw',
            'value' => 'Yii::app()->user->isAdmin() ?
          "<a href=\'index.php?r=plan/view&id=$data->id\'   class=\'btn btn-small view\'  ><i class=\'icon-eye-open\'></i></a>
         <a href=\'index.php?r=plan/update&id=$data->id\'   class=\'btn btn-small view\'  ><i class=\'icon-pencil\'></i></a>
          <a href=\'javascript:void(0);\' onclick=\'delete_record(".$data->id.")\'   class=\'btn btn-small view\'  ><i class=\'icon-trash\'></i></a>
         ": "<a href=\'index.php?r=plan/view&id=$data->id\'   class=\'btn btn-small view\'  ><i class=\'icon-eye-open\'></i></a>"',
            'htmlOptions' => array('style' => 'width:120px; text-align: center')
        ),
    ),
));
?>
<script type="text/javascript">
    function delete_record(id) {
        var id;
        this.id = id;
        bootbox.confirm("<img src='images/warning.png'/>  ¿Está seguro de eliminar este plan de estudios?<br/><br/> ", function (result) {
                if (!result) return;
                var data = "id=" + id;
                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->createAbsoluteUrl("plan/delete"); ?>',
                data: data,
                success: function (data) {
                    if (data == "true") {
                        $.fn.yiiGridView.update('examen-grid', {
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
            message: "<img src='images/warning.png'/>  Se eliminarán todos los planes.<br/> <br/>Por seguridad debe ingresar la contraseña de adminsitrador<br/><br/><input type='password' id ='pass' class='span3' ></input>",
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
                            url: '<?php  echo Yii::app()->createAbsoluteUrl("plan/deleteAll"); ?>',
                            data: {pass: $('#pass').val()},
                            success: function (data) {
                                console.log(data);
                                if (data == "true") {
                                    $.fn.yiiGridView.update('plan-grid', {
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
 
