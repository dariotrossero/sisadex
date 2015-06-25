<?php  

 
		
		 echo "<div class='printableArea'>";
     echo "<div id='tituloGridView'>";
	         echo "<h3>".$model->materia->nombreMateria."</h3>";
	         echo "</div><hr />";
	         $this->widget('bootstrap.widgets.TbDetailView',array(
			'data'=>$model,
      'type'=>'striped',
			'attributes'=>array(
						
		
		 array(
              
              // Nombre de la columna en el CGridView
              'name' => 'fechaExamen',
              // Nombre del dato en el modelo
              'value' => Utils::dateToDMYLong($model->fechaExamen,true),
              ),
		 array(
              'header' => 'Tipo de examen',
              // Nombre de la columna en el CGridView
              'name' => 'tipoexamen_id',
              // Nombre del dato en el modelo
              'value' => $model->tipoexamen->nombreTipoExamen,
              ),
		'diasPreparacion',
		'descripcionExamen'
		
			),
		));
	        
	  echo "<hr /><h4>Próximos 5 examenes de ".$model->materia->nombreMateria."</h4>";

    $this->widget('bootstrap.widgets.TbGridView', array(
      'id' => 'examen-grid',
      'type' => 'condensed',

      'dataProvider' => $model->getNextExams(),
      'columns'=>
      array(
           array(
              'header'=>'Fecha',
              // Nombre de la columna en el CGridView
              // Nombre del dato en el modelo
              'value' => 'Utils::dateToDMYLong($data->fechaExamen,false)',
               'htmlOptions' => array(
                  'style' => 'width:350px'
              ),
              ),

          array(
              'header' => 'Tipo',
              // Nombre de la columna en el CGridView
              
              // Nombre del dato en el modelo
              'value' => '$data->tipoexamen->nombreTipoExamen',
             ),
          
        

      ))); 

	         echo "</div>";