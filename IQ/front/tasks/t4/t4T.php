<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>IQ Task 4</title>

<script src='/opt/kwynn/js/utils.js'></script>
<script src='tasks/t4/t4.js'></script>

<link rel='stylesheet' href='common.css'>

<script>
	var KWIQT4A = '<?php echo($this->obo->oanswer . ''); ?>';
</script>

<style>
	.e4p10 { width: 40vw;	 }
	.e4p20 { 
		width:  4.7em;
		height: 2.5em; 
		font-size: 175%;
	}

	
</style>
</head>
<body class='e34'>
	
<div>
	<div class='thomasOuterColor' style='top: 0; margin: 2em auto 0 auto; width: 40vw; height: 10em; position: relative; '>
		<div class='e34p10 e4p10' >
			<?php for ($i=0; $i < count($this->obo->oqa); $i++) { 
				$num = $this->obo->oqa[$i];
				$t = '';
				$t .= '<div class="e34p20 e4p20" onclick="t3inter.onclick(this);" data-iamn="' . $num . '" >';
				$t .= '<div class="e34p30" >';
				$t .= $num;
				$t .= '</div>';
				$t .= '</div>';				
				echo($t);
			} ?>
			
		</div>
	</div>
</div>
</body>
</html>
