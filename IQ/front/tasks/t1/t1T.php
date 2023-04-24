<style>

	.t110 { 
		/* top right bottom left */
		margin: 5em auto 0 auto; 
		position: relative; 
		top: 2em; 
		width: 20em; 

		background-color: white; 
		padding: 2em 0 2em 0; 
		font-size: 150%;
	}
	
	.tqspecp10 {
		background-color: blue;
		width : 12em;
		height:  5em;
		margin: auto 1em auto 1em;
		display: inline-block;
		color: white;
		vertical-align: middle; 
		position: relative;
		
	}
	
	.tqpar { 
		padding-top: 4em; 
		margin-top: 2em; 
		
	}
	
	.clicki10 { 
		position: absolute;
		bottom: 1em;
		width: fit-content;
		margin-left: auto;
		margin-right: auto; 
		left: 0;
		right: 0;
	}
	
	.tqspec20 { 
		margin: 0;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		font-size: 150%;
		visibility: hidden;
	}
	
</style>

<?php $this->dbidjs(); ?>

</head>
<body>
	
<div style='text-align: center; '>
	
	<div style='visibility: hidden; font-size: 150%; margin-top: 0.5em; ' id='esrepeat'><?php echo($this->quaps->q0); ?>
		
	</div>
	
						<!-- top right bottom left -->
	<div class='thomasOuterColor' style='top: 0; margin: -6em auto 0 auto; width: 40em; height: 24em; position: relative; '>
		<div class='t110'>
			<div id='estatement'					   ><?php echo($this->quaps->q0); ?></div>
			<div id='equestion'  style='display: none'><?php echo($this->quaps->q);  ?></div>
		</div>
		
		<div class='tqpar'>
			
			<?php for($i=0; $i < IQTask1Front::ann; $i++) { 
				$name = $this->quaps->opts[$i];
				$ict  = ' data-iscor="';
				$ict .= $this->quaps->correct === $name ? 1 : 0;
				$ict .= '" ';
				$ict .= " data-a='$name' ";
	
				?>
			<div class='tqspecp10' <?php echo($ict); ?> data-isqp='1'>
				<div class='tqspec20' data-isqname='1'  ><?php echo($name);?>
			</div>
			</div>
			<?php }	?>
		</div>
		
		<div class='clicki10' id='eclsc'>Click the screen when you are ready to continue.</div>
	</div>
	<div style='display: none; visibility: hidden; margin-top: 2em;' id='ebagain'>
		<button onclick='location.reload();' style='font-size: 160%; '>again</button>
		<a href='template.php' style='color: black; text-decoration: none;'>
			<button style='font-size: 110%; margin-left: 5em; '>menu</button>
		</a>
	</div>
</div>
	
	<?php menuSide(); ?>
	
</body>
</html>
