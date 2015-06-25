<?php
/* @var $this PlanController */
/* @var $model Plan */
/* @var $form CActiveForm */
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/forms.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/dragDrop.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app() -> baseUrl . '/js/basic/jquery.js');

Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/dragDrop.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/formPlan.css');
?>


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
  <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
   'id'=>'plan-form',
   'enableAjaxValidation'=>false,
   'method'=>'post',
   'type'=>'horizontal',
   'htmlOptions'=>array(
    'enctype'=>'multipart/form-data'
    )
    )); ?>
    
    <div class="alert alert-error " id="msjError" style="">Ya existe un plan de la carrera y año ingresados.</div>

    <?php echo $form->errorSummary($model,'Por favor corrija los siguientes errores de ingreso:', null,array('class'=>'alert alert-error')); ?>

    <div class="control-group">		
     <div class="span4">
       <?php echo $form->label($model,'anioPlan'); ?>
       <?php echo $form->dropDownList($model,'anioPlan', array('materia_id'=>'-Por favor seleccione-')+$arrYears); ?>

     </div> 
     <div class="span4">  
      <?php echo $form->label($model,'Carrera_id'); ?>
      <?php echo $form->dropDownList($model, 'Carrera_id', array('materia_id'=>'-Por favor seleccione-')+CHtml::listData(Carrera::model()->findAll(), 'id', 'nombreCarrera'),array('class'=>'span4'));?>
      <input name="result"  id="formularioMaterias" type=hidden value="">


    </div>   
  </div>



  
   <div class="ActionButtons" id="buttons-plan">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
     'buttonType'=>'submit',
     'type'=>'primary',
     'icon'=>'ok white',  
     'label'=>$model->isNewRecord ? 'Crear' : 'Guardar',
     'htmlOptions'=>array('onclick' => 'procesarMaterias()'),
     )); ?>
     
   </div>
 





 <?php $this->endWidget(); ?>
</div> 
<!-- Form -->
<div id="plan-label"> Arrastra los elementos para armar el plan </div>
<div id="container_Mat_Tabla">
  <div  id="materias" class="drop" ondrop="dropMateria(this, event)" ondragenter="return false" ondragover="return false" > 

      <!-- Generacion de las materias dropeables -->
      <?php

      $materias=Materia::model()->getTodasLasMaterias('nombreMateria'); 
      echo '<ul>';
      foreach($materias as $item) {
          $codigo=$item->id;
          $nombre=$item->nombreMateria;
          echo '<span draggable="true" class="materia" id="'.$codigo.'" ondragstart="dragMateria(this, event)">'.$codigo.'-'.$nombre.'</span>';
      }

      echo '</ul>';
      ?>
      <!-- FIN Generacion de las materias dropeables -->


  </div>

  <div id="tabla">
    <table class="tg-table-light">
      <tr>
        <th>1º Cuatrimestre</th>
        <th>2º Cuatrimestre</th>
      </tr>
      <tr class="tg-even">
        <td class="anio" colspan="2">1º Año</td>
      </tr>
      <tr>
        <td id="c1" class="drop"  ondrop="dropMateria(this, event)" ondragenter="return false" ondragover="return false"></td>
        <td id="c2" class="drop"  ondrop="dropMateria(this, event)" ondragenter="return false" ondragover="return false"></td>
      </tr>
      <tr class="tg-even">
        <td class="anio" colspan="2">2º Año</td>
      </tr>
      <tr>
         <td id="c3" class="drop"  ondrop="dropMateria(this, event)" ondragenter="return false" ondragover="return false"></td>
        <td id="c4" class="drop"  ondrop="dropMateria(this, event)" ondragenter="return false" ondragover="return false"></td>
      </tr>
      <tr class="tg-even">
        <td class="anio" colspan="2">3º Año</td>
      </tr>
      <tr>
       <td id="c5" class="drop"  ondrop="dropMateria(this, event)" ondragenter="return false" ondragover="return false"></td>
        <td id="c6" class="drop"  ondrop="dropMateria(this, event)" ondragenter="return false" ondragover="return false"></td>
      </tr>
      <tr class="tg-even">
        <td class="anio" colspan="2">4º Año</td>
      </tr>
      <tr>
        <td id="c7" class="drop"  ondrop="dropMateria(this, event)" ondragenter="return false" ondragover="return false"></td>
        <td id="c8" class="drop"  ondrop="dropMateria(this, event)" ondragenter="return false" ondragover="return false"></td>
      </tr>
      <tr class="tg-even">
        <td class="anio" colspan="2">5º Año</td>
      </tr>
      <tr>
        <td id="c9" class="drop"  ondrop="dropMateria(this, event)" ondragenter="return false" ondragover="return false"></td>
        <td id="c10" class="drop"  ondrop="dropMateria(this, event)" ondragenter="return false" ondragover="return false"></td>
      </tr>
    </table>
  </div>
</div>


<script type="text/javascript">
 $('#Plan_anioPlan, #Plan_Carrera_id').change(function(){
  $(".alert-error").slideUp('fast');
                
                var anioPlan = $('#Plan_anioPlan').val();  // el "value" de ese <option> seleccionado
                var Carrera_id= $('#Plan_Carrera_id').val();

              console.log(anioPlan); console.log(Carrera_id);
                
                var action = 'index.php?r=plan/TestExistsPlan&anioPlan='+anioPlan+'&Carrera_id='+Carrera_id;


                // se pide al action la lista de productos de la categoria seleccionada
                //
                $('#reportarerror').html("");
                $.getJSON(action, function(respuesta) {
                         if(respuesta=="true") {
                          $('#msjError').slideDown('fast');

                         }
                         else {
                                $('#msjError').slideUp('fast');
                         }
                        
                   
                }).error(function(e){ $('#reportarerror').html(e.responseText); });                
        });


</script>
