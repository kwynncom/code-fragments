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
		padding-right: 0.5em; 
		padding-left : 0.5em;
	}
	
	td.q2 { 
		padding-top: 0.0em; 
		height: 1em; 
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
		font-size: 250%; 
		margin: 0.5em auto 0 auto;
		text-align: center;
	}
	
</style>
</head>
<body>
<div>
<div class='thomasOuterColor' style='top: 0; margin: 2em auto 0 auto; width: 40vw; height: 26em; position: static; '>
	<table class='t110'>

		<?php 		
			$t = '';
			$a = (array) $this->quaps->q;
			
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
			$c .= ' onclick=\'t2inter.onclick(this, ' . ($i === $this->quaps->correctAnswer ? 'true' : 'false') . ');\' ';
			
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
	
	<?php menuSide(); ?>
	
</body>
</html>
