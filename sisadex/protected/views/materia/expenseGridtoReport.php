<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="30px">
		      Código		</th>
 		<th width="280px">
		      Materia		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo "  ".$row->id; ?>
		</td>
       		<td>
			<?php echo "  ".$row->nombreMateria; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>
