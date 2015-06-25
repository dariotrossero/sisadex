<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="180px">
		      Tipo de examen		</th>
 		
 		
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo "  ".$row->nombreTipoExamen; ?>
		</td>
       		
       		
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>
