<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>IQ report</title>

<link rel='stylesheet' href='./../common.css'>

<style>
    body { font-family: sans-serif; }
</style>
</head>
<body>
	<div style='margin-left: 1em; '>
	<div><p>Qs: <?php echo($tot); ?></p>
		<p>Cor: <?php echo($cor); ?></p>
	</div>
	
	<div><?php foreach($a as $r) { 
		$t  = '';
		$t .= kwifs($r, 'q0', ['kwiff' => '']);
		if ($t) $t .= ' ';
		$t .= $r['q'] . ' ';
		$t .= $r['correctAnswer'] . '. result: ';
		$t .= $r['gotCorrect'] ? 'correct' : 'wrong';
		
		
		?>
		<pre><?php echo($t); ?></pre>
	<?php } ?>
		
	</div>
	
	
	<?php 	require_once(__DIR__ . '/../commonFront.php'); menuSide();	?>
	
	</div>
</body>
</html>

