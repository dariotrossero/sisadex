<?php  
 $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'button',
			'type'=>'primary',
                        'icon'=>'plus white', 
			'label'=>'Create',
			'htmlOptions'=>array('onclick'=>'renderCreateForm();'),
		));
		echo " ";
                $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'button',
			'type'=>'primary',
                        'icon'=>'edit white', 
			'label'=>'Update',
			'htmlOptions'=>array('onclick'=>'renderUpdateForm('.$model->Materia_id.');'),
		));
		
		
		echo " ";
		$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'button',
			'type'=>'primary',
                        'icon'=>'trash white', 
			'label'=>'Delete',
			'htmlOptions'=>array('onclick'=>'delete_record('.$model->Materia_id.');'),
		));
		
		echo " ";
		$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'button',
			'type'=>'primary',
                        'icon'=>'print white', 
			'label'=>'Print',
			'htmlOptions'=>array('onclick'=>'print();'),
		));
		
		 echo "<div class='printableArea'>";
	         echo "<h1>View materia-plan #".$model->Materia_id."</h1><hr />";
	         
	         $this->widget('bootstrap.widgets.TbDetailView',array(
			'data'=>$model,
			'attributes'=>array(
						'Materia_id',
		'Plan_id',
		'anio',
		'cuatrimestre',
			),
		));
	         echo "</div>";