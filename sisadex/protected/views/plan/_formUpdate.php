<?php
/* @var $this PlanController */
/* @var $model Plan */
/* @var $form CActiveForm */
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/formUpdatePlan.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/dragDrop.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app() -> baseUrl . '/js/basic/jquery.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/dragDrop.css');

?>

<body onload="<?php echo Yii::app()->controller->onloadFunction; ?>">
<div class="form">


<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'plan-form',
  // Please note: When you enable ajax validation, make sure the corresponding
  // controller action is handling ajax validation correctly.
  // There is a call to performAjaxValidation() commented in generated controller code.
  // See class documentation of CActiveForm for details on this.
  'enableAjaxValidation'=>false,
)); ?>
     	
	

	<?php echo $form->errorSummary($model,'Por favor corrija los siguientes errores de ingreso:', null,array('class'=>'alert alert-error')); ?>
        		
   <div class="control-group">	
       <div class="span6">
    	 <h3> <?php echo $model->carrera->nombreCarrera ?></h3>
       </div>   	
    		<div class="span4"><h3>	<?php echo $model->anioPlan ?></h3>
        </div>
    	

    	<?php echo $form->hiddenField($model,'anioPlan',array('type'=>"hidden",'size'=>2,'maxlength'=>2)); ?>
      <?php echo $form->hiddenField($model,'Carrera_id',array('type'=>"hidden",'size'=>2,'maxlength'=>2)); ?>
    	<input name="result"  id="formularioMaterias" type=hidden value="">

                       
  </div>


  <div class="ActionButtons" id="buttons-plan">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
      'buttonType'=>'submit',
      'type'=>'primary',
      'icon'=>'ok white',
        'htmlOptions' => array('onclick' => 'procesarMaterias()'),  
      'label'=>$model->isNewRecord ? 'Crear' : 'Guardar',
    )); ?>
    
  </div>


<?php $this->endWidget(); ?>



</div><!-- form -->


<div id="plan-label"> Arrastra los elementos para armar el plan </div>

<div id="container_Mat_Tabla">

    <!-- <span>Materias</span> -->
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

        echo '<ul>';
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

<?php
//Arreglo que luego será convertido a un array js

 $arregloMaterias = array();


//Obtengo todas las materias con año y cuatrimestre de un plan dado y armo un array asociativo con clave= codigo materia valor=id del cuatrimestre
$array_php=MateriaPlan::model()->findAll(
array("condition"=>"Plan_id =  $model->id"));
 
 foreach ($array_php as $key) {
   $materiaCodigo =$key->Materia_id;
 
   $spanId = $this->getTableId($key->anio,$key->cuatrimestre);
   $arregloMaterias[$materiaCodigo]=$spanId;

   
 }
 
 


?>




<script type="text/javascript">
function fillMateriasOnTable() {

 var arreglo = new Array();

    <?php
    foreach($arregloMaterias as $key => $value)  {
    ?>

    
        arreglo[<?php echo $key; ?>] = "<?php echo $value; ?>";
        moverMateria(<?php echo $key; ?>,<?php echo $value; ?>);
    <?php
      }
      
    ?>



}


function moverMateria(codigoMateria,cuatrimestreId) {
 
  document.getElementById('c'+cuatrimestreId).appendChild(document.getElementById(codigoMateria));

}

</script>




</div>

