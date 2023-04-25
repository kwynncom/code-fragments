<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>IQ report</title>

<link rel='stylesheet' href='./../common.css'>
<link rel='stylesheet' href='report.css'>

</head>
<body>
	<div style='margin-left: 1em; '>
		
		<?php require_once('reportT10Head.php'); ?>

	
	<div style='margin-top: 0.5em; '>
		<?php foreach($a as $r) { 
		$t  = '';
		$t .= '<div>';
		$t .= '<div class="check">' . ($r['gotCorrect'] ? '&check;' : 'X') . '</div>';
		$t .= kwifs($r, 'q0', ['kwiff' => '']);
		if ($t) $t .= ' ';
		
		switch ($r['taskn']) {
			case 1 : $t .= $r['q'] . ' '; break;
			case 2 : require_once('task2QFmt.php'); $t .= gett2QFmt($r['q']); break;
			default: kwas(false, 'report head bad task n - 2004');
		}
		
		
		$t .= ' ' . $r['correctAnswer'] . '';
		$t .= ' '  . $r['userAnswer']    . '';		

		$t .= '</div>' . "\n";

		
		
		?>
		<?php echo($t); ?>
	<?php } ?>
		
	</div>
	
	
	<?php 	require_once(__DIR__ . '/../commonFront.php'); menuSide();	?>
	
	</div>
</body>
</html>
