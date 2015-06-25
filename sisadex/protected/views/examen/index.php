<?php
$this->pageTitle = Yii::app()->name . ' - Examenes.';
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
    <h1>Examenes</h1>
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
            'label' => 'Eliminar todos los examenes',
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
            'label' => 'Eliminar todos mis examenes',
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
        //'codigoExamen',
        //   array(
        //            'class'=>'CCheckBoxColumn',
        //            'visible' => Yii::app()->user->isAdmin(),
        //           'selectableRows' => null,
        //          'headerHtmlOptions'=>array(
        //           'style' => 'width:1px'
        //  ),
        //),
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
                'style' => 'width:550px'
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
                'style' => 'width:200px'
            ),
            // Opciones HTML
            'filter' => CHtml::listData(Tipoexamen::model()->findAll(array('order' => 'nombreTipoExamen')), 'id', 'nombreTipoExamen') // Colocamos un combo en el filtro
        ),
        //array ('name'=>'TipoExamen_codigoTipoExamen','value'=>'$data->tipoExamenCodigoTipoExamen->nombreTipoExamen','type'=>'text',),
        array(
            'type' => 'raw',
            'value' => ' Yii::app()->user->getName()==$data->materia_id || Yii::app()->user->isAdmin() ?  
               "<a href=\'javascript:void(0);\' onclick=\'renderView(".$data->id.")\'   class=\'btn btn-small view\'  ><i class=\'icon-eye-open\'></i></a><a href=\'index.php?r=examen/update&id=$data->id\' onclick=\'renderUpdateForm(".$data->id.")\'   class=\'btn btn-small view\'  ><i class=\'icon-pencil\'></i></a>
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
        bootbox.confirm("<img src='images/warning.png'/>  ¿Está seguro de eliminar este examen?<br/><br/>", function (result) {
            if (!result) return;
            //  $('#ajaxtest-view-modal').modal('hide');
            var data = "id=" + id;
            jQuery.ajax({
                type: 'POST',
                url: '<?php
  echo Yii::app()->createAbsoluteUrl("examen/delete");
?>',
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
            message: "<img src='images/warning.png'/>  Se eliminarán todos los examenes<br/> <br/>Por seguridad debe ingresar la contraseña de adminsitrador<br/><br/><input type='password' id ='pass' class='span4' ></input>",
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
                                    $.fn.yiiGridView.update('examen-grid', {
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
    function delete_all_my_records(materia) {
        bootbox.dialog({
            className: "delete_all_my_records",
            message: "<img src='images/warning.png'/>  Se eliminarán todos los examenes.<br/> <br/>Por seguridad debe ingresar su contraseña<br/><br/><input type='password' id ='pass' class='span4' ></input>",
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
                                pass: $('#pass').val()},
                            success: function (data) {
                                console.log(data);
                                if (data == "true") {
                                    $.fn.yiiGridView.update('examen-grid', {
                                    });
                                }
                                else
                                    bootbox.alert("Contraseña incorrecta.");
                            },
                            error: function (data) { // if error occured
                                //     alert(JSON.stringify(data)); 
                                // alert("Error occured.please try again");
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
