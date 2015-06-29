<?php
$this->pageTitle = Yii::app()->name . ' - Usuarios.';
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('users-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<div class="titulo">
    <h1>Usuarios</h1>
</div>
<?php
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Nuevo', 'icon' => 'icon-plus', 'url' => 'javascript:void(0);', 'linkOptions' => array('onclick' => 'renderCreateForm()')),
        array('label' => 'Listado', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Buscar', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
        // array('label'=>'Exportar a PDF', 'icon'=>'icon-download', 'url'=>Yii::app()->controller->createUrl('GeneratePdf'), 'linkOptions'=>array('target'=>'_blank'), 'visible'=>true),
        // array('label'=>'Exportar a Excel', 'icon'=>'icon-download', 'url'=>Yii::app()->controller->createUrl('GenerateExcel'), 'linkOptions'=>array('target'=>'_blank'), 'visible'=>true),
        array('label' => 'Cambiar contraseña', 'icon' => 'icon-lock', 'url' => Yii::app()->controller->createUrl('changePassword'), 'active' => false, 'linkOptions' => array()),
        array(
            'itemOptions' => array('id' => 'deleteAll',
                'onclick' => 'delete_all_records()'
            ),
            'label' => 'Eliminar todos los usuarios',
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
<?php $this->widget('bootstrap.widgets.TbAlert', array(
    'block' => true, // display a larger alert block?
    'fade' => true, // use transitions?
    'closeText' => '&times;', // close link text - if set to false, no close link is displayed
    'alerts' => array( // configurations per alert type
        'success' => array('block' => true, 'fade' => true, 'closeText' => '&times;'), // success, info, warning, error or danger
    ),
)); ?>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search', array(
        'model' => $model,
    )); ?>
</div><!-- search-form -->
<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'users-grid',
    'dataProvider' => $model->search(),
    'type' => $this->table_style,
    'template' => $this->table_template,
    'htmlOptions' => array('style' => 'width:750px'),
    'columns' => array(
        array('header' => 'Usuario',
            'name' => 'id',
            'value' => '$data->id'),
        array(
            'type' => 'raw',
            'value' => '"
		      <a href=\'javascript:void(0);\' onclick=\'delete_record(\"$data->id\")\'   class=\'btn btn-small view\'  ><i class=\'icon-trash\'></i></a>
		     "',
            'htmlOptions' => array('style' => 'width:30px;')
        ),
    ),
));
$this->renderPartial("_ajax_create_form", array("model" => $model));
?>
<script type="text/javascript">
    function delete_record(id) {
        var id;
        this.id = id;
        bootbox.confirm("<img src='" + baseUrl + "/images/warning.png'/> ¿Está seguro de eliminar el usuario?", function (result) {
            if (!result) return;
            //  $('#ajaxtest-view-modal').modal('hide');
            var data = "id=" + id;
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo Yii::app()->createAbsoluteUrl("users/delete"); ?>',
                data: data,
                success: function (data) {
                    if (data == "true") {
                        $.fn.yiiGridView.update('users-grid', {});
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
            message: "<img src='" + baseUrl + "/images/warning.png'/>  Se eliminarán todos los usuarios.<br/> <br/>Por seguridad debe ingresar la contraseña de adminsitrador<br/><br/><input type='password' id ='pass' class='span3' ></input>",
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
                            url: '<?php  echo Yii::app()->createAbsoluteUrl("users/deleteAll"); ?>',
                            data: {pass: $('#pass').val()},
                            success: function (data) {
                                console.log(data);
                                if (data == "true") {
                                    $.fn.yiiGridView.update('users-grid', {});
                                }
                                else
                                    bootbox.alert("Contraseña incorrecta.");
                            },
                            error: function (data) { // if error occured
                                //alert(JSON.stringify(data));
                                bootbox.alert("Se ha producido un error interno. Contacte al administrador.");
                            },
                            dataType: 'html'
                        });
                    }
                }
            }
        });
    }
</script>
 
