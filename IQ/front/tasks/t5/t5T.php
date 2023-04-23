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
	
	.et5p05 {
		margin: 1em auto 0 2em;
	}
	
	.et5p20 { font-family: Arial; 
		height: 1em;
		width : 1em;
		text-align: center;
		margin: 0 auto 0 auto; 
		font-size: 400%;
		color:  #0059b3;
		font-weight: bold;
	}
	
	.et5p20:nth-child(even) { 	}
	.et5p20:nth-child(odd)  {  	}
	
	.et5p10 {
		
		/* top right bottom left */
		display: inline-block;
		background-color: white;
		width:  6em;
		height: 9em;
		margin: 1em 1em 2em 1em;
		padding-top: 1em; 
	}
</style>
</head>
<body class='e34'>
	
<div class='thomasOuterColor' style='top: 0; margin: 2em auto 0 auto; width: 20em; height: 20em; position: relative; '>
	
	<div class='et5p05'>
	<?php 
	
		$t = '';
		for ($i=0; $i < self::othen; $i++) {
			$t .= '<div class="et5p10">';
			for ($j=0; $j < self::othen; $j++) {
				
				if ($this->obo->oia[$i][$j]['i']) $sc = -1; 
				$sc = 1;
				
				$tr  = '';
				$tr .= "transform: scaleX($sc) ";
	
				
				// transform: rotate(90deg);
				
				$rot = $this->obo->oia[$i][$j]['o'];
				$tr .= " rotate({$rot}deg); ";
				
				
				$s = " style='$tr' ";
				
				$t .= '<div class="et5p20" ' . $s;
				$t .= '>';
				$t .= 'R';
				$t .= '</div>';
			}
			$t .= '</div>';
		}
		echo($t);
		
		
	?>
	</div>

</div>

</body>
</html>
