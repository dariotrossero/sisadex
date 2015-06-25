<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      password		</th>
 		<th width="80px">
		      role		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo $row->id; ?>
		</td>
       		<td>
			<?php echo $row->password; ?>
		</td>
       		<td>
			<?php echo $row->role; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>
