<script>
	var KWIQT5A = <?php echo($this->quaps->correctAnswer); ?>;
</script>

<style>
	
	.et5p05 {
		margin: 1em auto 0 auto;
	}
	
	.et5p20 { font-family: Arial; 
		height: 1em;
		width : 1em;
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
	
	.e100 {
		display: inline-block;
		margin: 0em auto 2em auto;

		/*
		width: 16em;
		height: 6em; 
*/
	
	}
	
	.e150 {
		display: inline-block; 
		height: 2em;
		width:  2em;
		margin: 0em 0.2em 0em 0.2em;
		background-color: #3366ff;
		font-weight: bold;
		font-size: 250%;
		color: white;
		font-family: Helvetica;
		
		
	}
	
	.ep05 {
		top: 0; margin: 2em auto 0 auto; width: 20em; height: 20em; 
		text-align: center; 
		
	}
	

</style>
</head>
<body class='e34'>
	
<div class='thomasOuterColor ep05' >
	
	<div class='et5p05'><?php require_once('t5T10.php');?></div>
	<div class='e100'>
		<?php
		$t = '';
		for($i=self::clamin; $i <= self::clamax; $i++) {
			$t .=  '<div class="e150" onclick="t5inter.onclick(this, ' . $i . ');">';
			$t .= $i;
			$t .= '</div>';
			
		} 
		
		echo($t);
		
		?>
	</div>

</div>

	<?php menuSide(); ?>
	
</body>
</html>
