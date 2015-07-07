<?php
$this->pageTitle = Yii::app()->name . ' - Exámenes.';
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
  $('.search-form').toggle();
  return false;
});
$('.search-form form').submit(function(){
  $('#examen-grid').yiiGridView('update', {
    data: $(this).serialize()
  });
  return false;
});
");
?>
<div class="titulo">
    <h1>Exámenes</h1>
</div>
<?php
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array(
            'label' => 'Nuevo',
            'icon' => 'icon-plus',
            'url' => Yii::app()->controller->createUrl('create')
        ),
        array(
            'label' => 'Listado',
            'icon' => 'icon-th-list',
            'url' => Yii::app()->controller->createUrl('index'),
            'active' => true,
            'linkOptions' => array()
        ),
        array(
            'label' => 'Exportar a PDF',
            'icon' => 'icon-download',
            'url' => Yii::app()->controller->createUrl('GeneratePdf'),
            'linkOptions' => array(
                'target' => '_blank'
            ),
            'visible' => true
        ),
        array(
            'label' => 'Exportar a Excel',
            'icon' => 'icon-download',
            'url' => Yii::app()->controller->createUrl('GenerateExcel'),
            'linkOptions' => array(
                'target' => '_blank',
            ),
            'visible' => true
        ),
        array(
            'itemOptions' => array('id' => 'deleteAll',
                'onclick' => 'delete_all_records()'
            ),
            'label' => 'Eliminar todos los exámenes',
            'icon' => 'icon-remove-sign',
            'url' => '#',
            'linkOptions' => array(
                'class' => ' btn-danger btn-small '
            ),
            'visible' => Yii::app()->user->isAdmin(),
        ),
        array(
            'itemOptions' => array('id' => 'deleteAll',
                'onclick' => 'delete_all_my_records(' . Yii::app()->user->name . ')',
            ),
            'label' => 'Eliminar todos mis exámenes',
            'icon' => 'icon-remove-sign',
            'url' => '#',
            'linkOptions' => array(
                'class' => ' btn-danger btn-small '
            ),
            'visible' => !Yii::app()->user->isAdmin(),
        ),
    )
));
?>
<?php
$grid = $this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'examen-grid',
    'dataProvider' => $model->search(),
    'type' => $this->table_style,
    'template' => $this->table_template,
    //       'selectableRows' => '2',
    'filter' => $model,
    'columns' => array(
        array(
            'header' => 'Fecha',
            // Nombre de la columna en el CGridView
            'name' => 'fechaExamen',
            'headerHtmlOptions' => array(
                'style' => 'width:145px'
            ),
            // Nombre del dato en el modelo
            'value' => 'Yii::app()->dateFormatter->format("dd MMM y",strtotime($data->fechaExamen))',
            // Opciones HTML
            'filter' => CHtml::listData(Examen::model()->findAll(), 'fechaExamen', 'formattedDate') // Colocamos un combo en el filtro
        ),
        array(
            'header' => 'Materia',
            // Nombre de la columna en el CGridView
            'name' => 'materia_id',
            // Nombre del dato en el modelo
            'value' => '$data->materia->id." - ".$data->materia->nombreMateria',
            'headerHtmlOptions' => array(
                'style' => 'width:490px'
            ),
            // Opciones HTML
            'filter' => CHtml::listData(Materia::model()->getTodasLasMaterias('nombreMateria'), 'id', 'concatened') // Colocamos un combo en el filtro
        ),
        array(
            'header' => 'Tipo de examen',
            // Nombre de la columna en el CGridView
            'name' => 'tipoexamen_id',
            // Nombre del dato en el modelo
            'value' => '$data->tipoexamen->nombreTipoExamen',
            'headerHtmlOptions' => array(
                'style' => 'width:240px'
            ),
            // Opciones HTML
            'filter' => CHtml::listData(Tipoexamen::model()->findAll(array('order' => 'nombreTipoExamen')), 'id', 'nombreTipoExamen') // Colocamos un combo en el filtro
        ),
        array(
            'type' => 'raw',
            'value' => ' Yii::app()->user->getName()==$data->materia_id || Yii::app()->user->isAdmin() ?  
               "<a href=\'javascript:void(0);\' onclick=\'renderView(".$data->id.")\'   class=\'btn btn-small view\'  ><i class=\'icon-eye-open\'></i></a><a href=\'update/$data->id\'  class=\'btn btn-small view\'  ><i class=\'icon-edit\'></i></a>
          <a href=\'javascript:void(0);\' onclick=\'delete_record(".$data->id.")\'   class=\'btn btn-small view\'  ><i class=\'icon-trash\'></i></a>
         ": "<a href=\'javascript:void(0);\' onclick=\'renderView(".$data->id.")\'   class=\'btn btn-small view\'  ><i class=\'icon-eye-open\'></i></a>"',
            'headerHtmlOptions' => array(
                'style' => 'width:95px;text-align:left'
            )
        )
    )
));
$this->renderPartial("_ajax_view");
?>
<script type="text/javascript">
    function delete_record(id) {
        var id;
        this.id = id;
        bootbox.confirm("<img src='" + baseUrl + "/images/warning.png'/>  ¿Está seguro de eliminar este examen?<br/><br/>", function (result) {
            if (!result) return;
            var data = "id=" + id;
            jQuery.ajax({
                type: 'POST',
                url: '<?php
  echo Yii::app()->createAbsoluteUrl("examen/delete");
?>',
                data: data,
                success: function (data) {
                    if (data == "true") {
                        $.fn.yiiGridView.update('examen-grid', {});
                    }
                    else
                        alert("deletion failed");
                },
                error: function (data) { // if error occured
                    bootbox.alert("Se ha producido un error interno. Contacte al administrador.");
                },
                dataType: 'html'
            });
        });
    }
    function delete_all_records() {
        bootbox.dialog({
            message: "<img src='" + baseUrl + "/images/warning.png'/>  Se eliminarán todos los exámenes<br/> <br/>Por seguridad debe ingresar la contraseña de adminsitrador<br/><br/><input type='password' id ='pass' class='span4' ></input>",
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
                            url: '<?php  echo Yii::app()->createAbsoluteUrl("examen/deleteAll"); ?>',
                            data: {pass: $('#pass').val()},
                            success: function (data) {
                                console.log(data);
                                if (data == "true") {
                                    $.fn.yiiGridView.update('examen-grid', {});
                                }
                                else
                                    bootbox.alert("Contraseña incorrecta.");
                            },
                            error: function (data) { // if error occured
                                bootbox.alert("Se ha producido un error interno. Contacte al administrador.");
                            },
                            dataType: 'html'
                        });
                    }
                }
            }
        });
    }
    function delete_all_my_records(materia) {
        bootbox.dialog({
            className: "delete_all_my_records",
            message: "<img src='" + baseUrl + "/images/warning.png'/>  Se eliminarán todos los exámenes.<br/> <br/>Por seguridad debe ingresar su contraseña<br/><br/><input type='password' id ='pass' class='span4' ></input>",
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
                            url: '<?php  echo Yii::app()->createAbsoluteUrl("examen/deleteAllMyRecords"); ?>',
                            data: {
                                mat: materia,
                                pass: $('#pass').val()
                            },
                            success: function (data) {
                                console.log(data);
                                if (data == "true") {
                                    $.fn.yiiGridView.update('examen-grid', {});
                                }
                                else
                                    bootbox.alert("Contraseña incorrecta.");
                            },
                            error: function (data) { // if error occured
                                bootbox.alert("Se ha producido un error. Contacte al administrador.");
                            },
                            dataType: 'html'
                        });
                    }
                }
            }
        });
    }
</script>
