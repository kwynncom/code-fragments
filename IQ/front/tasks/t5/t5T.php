<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>IQ Task 5</title>

<script src='/opt/kwynn/js/utils.js'></script>
<script src='tasks/t5/t5.js'></script>

<link rel='stylesheet' href='common.css'>

<script>
	var KWIQT5A = '<?php echo($this->obo->omatches . ''); ?>';
</script>

<style>
	.et5p10 {
		display: inline-block;
		background-color: white;
		width: 5em;
		height: 10em;
	}
</style>
</head>
<body class='e34'>
	
<div class='thomasOuterColor' style='top: 0; margin: 2em auto 0 auto; width: 20em; height: 20em; position: relative; '>
	<?php 
	
		$t = '';
		for ($i=0; $i < self::othen; $i++) {
			$t .= '<div class="et5p10">';
			for ($j=0; $j < self::othen; $j++) {
				
			}
			$t .= '</div>';
		}
		echo($t);
		
		
	?>

</div>

</body>
</html>
