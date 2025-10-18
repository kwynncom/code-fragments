<?php $showD = true && ispkwd();

?>

<table>	
    <thead>
	<tr>
	    <th></th>
	    <th style='text-align: center; width: 2em; '>+d</th>
	    <th style='text-align: center; width: 2em; '>+h</th>
	    <th>earned to</th>
	    <?php if ($showD) { ?> 
		<th>per h</th> 
		<th style='text-align: center; width: 4em; '>+days</th>
	    <?php } ?>
	    <th>as of</th>
	</tr>
    </thead>

    <tbody>
	<?php foreach($a as $r) {  ?>
	    
	    <tr>
		<td><?php echo(substr($r['projectName'], 0, 3)); ?></td>
		<td style='text-align: center; '>
		    <?php
			echo(sprintf('%0.1f', $r['daysAhead']));
		    ?>
		</td>
		<td style='text-align: center; '>
		    <?php
			echo(sprintf('%0.1f', $r['hoursAhead']));
		    ?>
		</td>

		<td><?php 
		    if ($showD) $fmt = 'D, M d H:i';
		    else	$fmt = 'D H:i';
		    echo(date  ($fmt, $r['UEarnedTo'])); ?>
		</td>

		<?php if ($showD) { ?>
		    <td><?php echo('$' . sprintf('%0.2f', $r['dphNow'])); ?></td>


		    <td style=''>
			<?php
			    echo(sprintf('%0.5f', $r['daysAhead']));
			?>
		    </td>
		<?php } ?>
		<td><?php
		    echo(date('D H:i', $r['Ufile']));
		?>
		</td>

	    </tr>

	<?php } ?>
	

    </tbody>

</table>

