<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>IQ Task 2</title>

<script src='/opt/kwynn/js/utils.js'></script>
<script src='tasks/t2/t2.js'></script>

<link rel='stylesheet' href='common.css'>

<style>
	.t110 { 
		/* top right bottom left */
		padding-top: 1.5em; 
		margin: 1em auto 0 auto; 
		position: static; 
		font-size: 300%;
		font-weight: bold;
		font-family: "Helvetica";
	}
	
	td.qall {
		text-align: center;
		padding-right: 1em; 
		padding-left : 1em;
	}
	
	td.q2 { 
		padding-top: 0.7em; 
	}
	
	.eans {
		display: inline-block; 
		width: 1em;
		padding: 0.3em;
		margin: 0 0.15em 0 0.15em;
		background-color:   #3366ff;
		color: white;
	}
	
	.eansp { 
		font-size: 350%; 
		margin: 0.5em auto 0 auto;
		text-align: center;
	}
	
</style>
</head>
<body>
<div style=''>
<div class='thomasColor' style='top: 0; margin: 2em auto 0 auto; width: 40vw; height: 26em; position: static; '>
	<table class='t110'>

		<?php 		
			$t = '';
			$a = $this->obo->othea;
			
			for ($j=0; $j < self::clrows; $j++) {
				$t .= '<tr>';
				if ($j === 1) $st = ' class="qall q2" ';
				else		  $st = ' class="qall" ';
				for ($i=0; $i < self::clcols; $i++) {
					$t .= '<td';
					$t .= $st;
					$t .= '>';
					$t .= $a[$i][$j];
					
					$t .= '</td>';
				}
				$t .= '</tr>' . "\n";
			}
			
			echo($t);
		?>		
	</table>
	
	<div class='eansp'>

		<?php 
		
		$t = '';
		for ($i = 0; $i < self::clcols + 1; $i++) {
			
			$c  = '';
			$c .= ' onclick=\'t2inter.onclick(this, ' . ($i === $this->obo->ocorn ? 'true' : 'false') . ');\' ';
			
			$t .= "<div class='eans' $c>";
			$t .= $i;
			$t .= '</div>';
		}
		
		$t .= "\n";
		echo($t);
		?>
	</div>
	
</div>
</div>
</body>
</html>
