<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="30px">
		      Año		</th>
 		<th width="280px">
		      Carrera		</th>
 		
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo "  ".$row->anioPlan; ?>
		</td>
       		<td>
			<?php echo "  ".$row->carrera->nombreCarrera; ?>
		</td>
       		
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>
