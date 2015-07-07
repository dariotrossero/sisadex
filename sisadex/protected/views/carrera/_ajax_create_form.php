<div id='carrera-create-modal' class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Nueva carrera</h3>
  </div>
  <div class="modal-body">
    <div class="form">
     <?php
     $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
       'id'=>'carrera-create-form',
       'enableAjaxValidation'=>true,
       'enableClientValidation'=>true,
       'method'=>'post',
       'action'=>array("carrera/create"),
       'type'=>'horizontal',
       'htmlOptions'=>array(
         'onsubmit'=>"return false;",/* Disable normal form submit */
         ),
       'clientOptions'=>array(
        'validateOnType'=>true,
        'validateOnSubmit'=>true,
        'afterValidate'=>'js:function(form, data, hasError) {
         if (!hasError)
         {    
          create();
        }
      }'
      ),
      )); ?>
      <fieldset>
       <p class="note">Campos obligatorios <span class="required">*</span></p>
       <?php echo $form->errorSummary($model,'Por favor corrija los siguientes errores de ingreso:', null,array('class'=>'alert alert-error span12')); ?>
       <div class="control-group">
         <div class="span4">
          <div class="row">
            <?php echo $form->labelEx($model,'id'); ?>
            <?php echo $form->textField($model,'id',array('size'=>15,'maxlength'=>15,'class'=>'span1')); ?>
            <?php echo $form->error($model,'id'); ?>
          </div>
          <div class="row">
           <?php echo $form->labelEx($model,'nombreCarrera'); ?>
           <?php echo $form->textField($model,'nombreCarrera',array('size'=>70,'maxlength'=>70,'class'=>'span5')); ?>
           <?php echo $form->error($model,'nombreCarrera'); ?>
         </div>
       </div>
     </div>

   </div><!--end modal body-->
   
   <div class="modal-footer">
     <div>
       <?php
       $this->widget('bootstrap.widgets.TbButton', array(
        'icon'=>'remove',
        'label'=>'Cerrar',
        'htmlOptions' => array( 
          'onclick' => "$('#carrera-create-modal').modal('hide')",
          )  
          )); ?>
       <?php
       $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'primary',
        'icon'=>'ok white', 
        'label'=>$model->isNewRecord ? 'Crear' : 'Guardar',
        )
       );
       ?>
     </div> 
   </div><!--end modal footer-->	
 </fieldset>

 <?php
 $this->endWidget(); ?>

</div>

</div><!--end modal-->

<script type="text/javascript">
function create()
{
 
 var data=$("#carrera-create-form").serialize();
 jQuery.ajax({
   type: 'POST',
   url: '<?php
   echo Yii::app()->createAbsoluteUrl("carrera/create"); ?>',
   data:data,
   success:function(data){
                //alert("succes:"+data); 
                if(data!="false")
                {
                  $('#carrera-create-modal').modal('hide');
               //   renderView(data);
               $.fn.yiiGridView.update('carrera-grid', {
                 
               });
               
             }
             
           },
   error: function(data) { // if error occured
	$('#carrera-create-modal').modal('hide');
       bootbox.alert("Se ha producido un error interno. Contacte al administrador.");
     },
     dataType:'html'
   });

}

function renderCreateForm()
{
  $('#carrera-create-form').each (function(){
    this.reset();
  });

  $('#carrera-view-modal').modal('hide');
  $('#carrera-create-modal').modal({
   show:true,
 });
}
</script>
