<table>	
    <thead>
	<tr>
	    <th>project</th>
	    <th>earned to</th>
	</tr>
    </thead>

    <tbody>
	<?php foreach($a as $r) {  ?>
	    
	    <tr>
		<td><?php echo($r['projectName']); ?></td>
		<td><?php echo($r['earnedTo']); ?></td>
	    </tr>

	<?php } ?>
	

    </tbody>

</table>

