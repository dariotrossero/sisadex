<?php
 $this->renderPartial("_ajax_update");
 $this->renderPartial("_ajax_create_form",array("model"=>$model));
 
$this->pageTitle=Yii::app()->name . ' - Tipos de examen.';
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('tipoexamen-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<div class="titulo"><h1>
<?php if (Yii::app()->user->isAdmin()) echo "Tipos de exámenes globales";
 else echo "Mis tipos de exámenes";
?>
</h1>
</div>
<?php 
$this->widget('bootstrap.widgets.TbMenu', array(
	'type'=>'pills',
	'items'=>array(
		array('label'=>'Nuevo', 'icon'=>'icon-plus', 'url'=>'javascript:void(0);','linkOptions'=>array('onclick'=>'renderCreateForm()')),
    array('label'=>'Listado', 'icon'=>'icon-th-list', 'url'=>Yii::app()->controller->createUrl('index'),'active'=>true, 'linkOptions'=>array()),
		array('label'=>'Buscar', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
		 array(
               'itemOptions'=>array('id' => 'deleteAll',
              'onclick'=>'delete_all_my_records('.Yii::app()->user->name.')',
              ),
              'label' => 'Eliminar todos mis tipos de examen',
              'icon' => 'icon-remove-sign',
              'url' => '#',
            
              'linkOptions' => array(
                  
                  'class'=>' btn-danger btn-small '
              ),
              'visible' => !Yii::app()->user->isAdmin(),
          ), array('label'=>'Eliminar tipos de examen', 'url'=>'#', 'icon' => 'icon-remove-sign',
               'visible' => Yii::app()->user->isAdmin(),
               'linkOptions' => array(
                  
                  'class'=>' btn-danger btn-small  '
              ), 'items'=>array(
                    array(
               'itemOptions'=>array(
                'id' => 'deleteAll',
              'onclick'=>'delete_all_global_records()'
              ),
              'label' => 'Solo globales',
              'url' => '#',
              'linkOptions' => array(
                  'class'=>' btn-small '
              ), 
              'visible' => Yii::app()->user->isAdmin(),
              ),
                 array(
               'itemOptions'=>array(
                'id' => 'deleteAll',
              'onclick'=>'delete_all_records()'
              ),
              'label' => 'Todos',
              'url' => '#',
              'linkOptions' => array(
                  'class'=>' btn-small '
              ), 
              'visible' => Yii::app()->user->isAdmin(),
              )
                   
                )),
     
	),
));
?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php
if (Yii::app()->user->isAdmin()) 
  $datos=$model->searchDefault();
else
  $datos=$model->searchPorMaterias(Yii::app()->user->name);
 $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'tipoexamen-grid',
	'dataProvider'=>$datos,
        'type'=>$this->table_style,
         'template'=>$this->table_template,
        'htmlOptions'=>array('style'=>'width:750px'),
	'columns'=>array(
		'nombreTipoExamen',
    'complejidad',
		
               array(
		     
		      'type'=>'raw',
		       'value'=>'"
		      
		      <a href=\'javascript:void(0);\' onclick=\'renderUpdateForm(".$data->id.")\'   class=\'btn btn-small view\'  ><i class=\'icon-pencil\'></i></a>
		      <a href=\'javascript:void(0);\' onclick=\'delete_record(".$data->id.")\'   class=\'btn btn-small view\'  ><i class=\'icon-trash\'></i></a>
		     "',
		      'htmlOptions'=>array('style'=>'width:80px; text-align: center')    
		     ),
        
	),
)); 
 ?>
 
<script type="text/javascript"> 
function delete_record(id)
{
  var id;
  this.id=id;
  
 bootbox.confirm("<img src='"+baseUrl+"/images/warning.png'/>  ¿Está seguro de eliminar este tipo de examen?<br/><br/> <p class='text-warning'>Se eliminarán todos los exámenes asociados a él.</p>", function(result) {
           
 if (!result) return;
    
 //  $('#ajaxtest-view-modal').modal('hide');
 
 var data="id="+id;
 
  jQuery.ajax({
   type: 'POST',
    url: '<?php echo Yii::app()->createAbsoluteUrl("tipoexamen/delete"); ?>',
   data:data,
success:function(data){
                 if(data=="true")
                  {
                      
                     $.fn.yiiGridView.update('tipoexamen-grid', {
                     
                         });
                 
                  } 
                 else
                  // alert("deletion failed");
                bootbox.alert("Se ha producido un error. Contacte al administrador.");
              },
   error: function(data) { // if error occured
        //   alert(JSON.stringify(data)); 
        // alert("Error occured.please try again");
         bootbox.alert("Se ha producido un error. Contacte al administrador.");
       //  alert(data);
    },
  dataType:'html'
  });
});  
}
function delete_all_global_records() {
 bootbox.dialog({
  message: "<img src='"+baseUrl+"/images/warning.png'/>  Se eliminarán todos los tipos de examen globales y los exámenes relacionados a ellos.<br/> <br/>Por seguridad debe ingresar la contraseña de administrador<br/><br/><input type='password' id ='pass' class='span4' ></input>",
  title: "Confirmar eliminación",
 buttons: {
        cancelar: {
      label: "Cancelar",
      className: "btn-default",
      callback: function(pass) {
     
      }
    },
    main: {
      label: "Eliminar",
      className: "btn-danger",
      callback: function(pass) {
     
  jQuery.ajax({
   type: 'POST',
    url: '<?php  echo Yii::app()->createAbsoluteUrl("tipoexamen/deleteAllGlobals"); ?>',
   data:{pass:$('#pass').val()},
          success:function(data){
              console.log(data);
                           if(data=="true")
                            {
                               $.fn.yiiGridView.update('tipoexamen-grid', {
                                   });
                            }
                           else
                             bootbox.alert("Contraseña incorrecta.");
                        },
             error: function(data) { // if error occured
                //     alert(JSON.stringify(data)); 
                  // alert("Error occured.please try again");
                 bootbox.alert("Se ha producido un error. Contacte al administrador.");
              },
  dataType:'html'
  });
      }
    }
  }
});
}
function delete_all_records() {
 bootbox.dialog({
  message: "<img src='"+baseUrl+"/images/warning.png'/>  Se eliminarán <strong>TODOS</strong> los tipos de examen y los exámenes relacionados a ellos.<br/> <br/>Por seguridad debe ingresar la contraseña de administrador<br/><br/><input type='password' id ='pass' class='span4' ></input>",
  title: "Confirmar eliminación",
 buttons: {
        cancelar: {
      label: "Cancelar",
      className: "btn-default",
      callback: function(pass) {
     
      }
    }
    ,
    main: {
      label: "Eliminar",
      className: "btn-danger",
      callback: function(pass) {
     
  jQuery.ajax({
   type: 'POST',
    url: '<?php  echo Yii::app()->createAbsoluteUrl("tipoexamen/deleteAll"); ?>',
   data:{pass:$('#pass').val()},
          success:function(data){
              console.log(data);
                           if(data=="true")
                            {
                                bootbox.alert("Se han eliminado todos los tipos de examen.");
                               $.fn.yiiGridView.update('tipoexamen-grid', {
                               
                                   });
                           
                            } 
                           else
                             bootbox.alert("Contraseña incorrecta.");
                        },
             error: function(data) { // if error occured
                //     alert(JSON.stringify(data)); 
                  // alert("Error occured.please try again");
                 bootbox.alert("Se ha producido un error. Contacte al administrador.");
              },
  dataType:'html'
  });
      }
    }
  }
});
}
function delete_all_my_records(materia) {
 bootbox.dialog({
  message: "<img src='"+baseUrl+"/images/warning.png'/>  Se eliminarán todos los tipos de examen y los exámenes relacionados a ellos.<br/> <br/>Por seguridad debe ingresar su contraseña<br/><br/><input type='password' id ='pass' class='span4' ></input>",
  title: "Confirmar eliminación",
 
 buttons: {
        cancelar: {
      label: "Cancelar",
      className: "btn-default",
      callback: function(pass) {
     
      }
    },
    main: {
      label: "Eliminar",
      className: "btn-danger",
      callback: function(pass) {
     
  jQuery.ajax({
   type: 'POST',
    url: '<?php  echo Yii::app()->createAbsoluteUrl("tipoexamen/deleteAllMyRecords"); ?>',
   data:{
    mat:materia,
    pass:$('#pass').val()},
    
          success:function(data){
              console.log(data);
                           if(data=="true")
                            {
                             
                               $.fn.yiiGridView.update('tipoexamen-grid', {
                               
                                   });
                           
                            } 
                           else
                             bootbox.alert("Contraseña incorrecta.");
                        },
             error: function(data) { // if error occured
                //     alert(JSON.stringify(data)); 
                  // alert("Error occured.please try again");
                 bootbox.alert("Se ha producido un error. Contacte al administrador.");
              },
  dataType:'html'
  });
      }
    }
  }
});
}
</script>
<style>
.nav .dropdown-toggle .caret {
  border-top-color:white;
}
.nav .dropdown-toggle:hover .caret, .nav .dropdown-toggle:focus .caret {
border-top-color: white;
border-bottom-color: white;
}
</style>
 
