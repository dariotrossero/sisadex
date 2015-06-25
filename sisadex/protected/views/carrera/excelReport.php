<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="30px">
		      Código		</th>
 		<th width="280px">
		      Carrera		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo "  ".$row->id; ?>
		</td>
       		<td>
			<?php echo "  ".$row->nombreCarrera; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>
