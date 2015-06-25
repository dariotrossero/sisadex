
<div id='tipoexamen-create-modal' class="modal hide fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">

		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">×</button>

		<h3>Nuevo Tipo de Examen</h3>

	</div>

	<div class="modal-body">

		<div class="form">

   <?php
			$form = $this->beginWidget ( 'bootstrap.widgets.TbActiveForm', array (
					'id' => 'tipoexamen-create-form',
					'enableAjaxValidation' => true,
					'enableClientValidation' => true,
					'method' => 'post',
					'action' => array (
							"tipoexamen/create" 
					),
					'type' => 'horizontal',
					'htmlOptions' => array (
							'onsubmit' => "return false;",/* Disable normal form submit */
                            ),
					'clientOptions' => array (
							'validateOnType' => true,
							'validateOnSubmit' => true,
							'afterValidate' => 'js:function(form, data, hasError) {
                                     if (!hasError)
                                        {    
                                          create();
                                        }
                                     }' 
					)
					 
			)
			 );
			?>
     	<fieldset>

				<p class="note">
					Campos obligatorios <span class="required">*</span>
				</p>
	

	<?php echo $form->errorSummary($model,null, null,array('class'=>'alert alert-error span12')); ?>
        		
   <div class="control-group">
					<div class="span4">

						<div class="row">
					  <?php echo $form->labelEx($model,'nombreTipoExamen'); ?>
					  <?php echo $form->textField($model,'nombreTipoExamen',array('size'=>70,'maxlength'=>70,'class'=>'span3')); ?>
					  <?php echo $form->error($model,'nombreTipoExamen'); ?>
				  </div>

						<div class="row">
            <?php echo $form->labelEx($model,'complejidad'); ?>
            <?php echo $form->textField($model,'complejidad',array('size'=>2,'maxlength'=>2,'class'=>'span1')); ?>
            <?php echo $form->error($model,'complejidad'); ?>
          </div>




					</div>
				</div>
		
		</div>
		<!--end modal body-->

		<div class="modal-footer">
			<div>

	
              <?php
														$this->widget ( 'bootstrap.widgets.TbButton', array (
																
																'icon' => 'remove',
																'label' => 'Cerrar',
																'htmlOptions' => array (
																		'onclick' => "$('#tipoexamen-create-modal').modal('hide')" 
																) 
														) );
														?>

      <?php
						
						$this->widget ( 'bootstrap.widgets.TbButton', array (
								'buttonType' => 'submit',
								'type' => 'primary',
								'icon' => 'ok white',
								'label' => $model->isNewRecord ? 'Crear' : 'Guardar' 
						) )

						;
						
						?>
	</div>
		</div>
		<!--end modal footer-->
		</fieldset>

<?php
$this->endWidget ();
?>

</div>

</div>
<!--end modal-->

<script type="text/javascript">
function create()
 {
 
   var data=$("#tipoexamen-create-form").serialize();
     


  jQuery.ajax({
   type: 'POST',
    url: '<?php
				echo Yii::app ()->createAbsoluteUrl ( "tipoexamen/create" );
				?>',
   data:data,
success:function(data){
                
                if(data!="false")
                 {
                  $('#tipoexamen-create-modal').modal('hide');
                //  renderView(data);
                    $.fn.yiiGridView.update('tipoexamen-grid', {
                     
                         });
                   
                 }
                 else
                 {
                 	  $('#tipoexamen-create-modal').modal('hide');
                 	   bootbox.alert("Ya existe un registro en la base de datos.");
                 }
                 
              },
   error: function(data) { // if error occured
		      //    alert(JSON.stringify(data)); 
			$('#tipoexamen-create-modal').modal('hide');
			bootbox.alert("Se ha producido un error interno. Contacte al administrador.");
         
    },

  dataType:'html'
  });

}

function renderCreateForm()
{
  $('#tipoexamen-create-form').each (function(){
  this.reset();
   });

  
  $('#tipoexamen-view-modal').modal('hide');
  
  $('#tipoexamen-create-modal').modal({
   show:true,
   
  });
}

</script>
