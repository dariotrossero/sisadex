<?php if ($model !== null):?>
<table border="1">

	<tr>
	
 		<th width="80px">
		      Fecha de examen		</th>
 		<th width="80px">
		      Tipo de examen		</th>
 		<th width="180px">
		      Materia		</th>
 		<th width="140px">
		      Descripción		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		
       		<td>
			<?php  echo "  ". Yii::app()->dateFormatter->format("dd MMM y",strtotime($row->fechaExamen)); ?>
		</td>
       		<td>
			<?php echo "  ".$row->tipoexamen->nombreTipoExamen; ?>
		</td>
       		<td>
			<?php  echo "  ". $row->materia->nombreMateria; ?>
		</td>
       		<td>
			<?php  echo "  ". $row->descripcionExamen; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>
