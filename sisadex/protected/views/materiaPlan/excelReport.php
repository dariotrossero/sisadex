<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      Materia_id		</th>
 		<th width="80px">
		      Plan_id		</th>
 		<th width="80px">
		      anio		</th>
 		<th width="80px">
		      cuatrimestre		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo $row->Materia_id; ?>
		</td>
       		<td>
			<?php echo $row->Plan_id; ?>
		</td>
       		<td>
			<?php echo $row->anio; ?>
		</td>
       		<td>
			<?php echo $row->cuatrimestre; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>
