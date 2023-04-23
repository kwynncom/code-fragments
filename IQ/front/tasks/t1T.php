<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>IQ Tasks</title>

<script src='/opt/kwynn/js/utils.js'></script>
<script src='tasks/t1.js'></script>

<style>
    body { font-family: Arial; }
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
</head>
<body>
	
<div style='text-align: center; '>
	
	<div style='visibility: hidden; font-size: 150%; margin-top: 3em; ' id='esrepeat'><?php echo($this->obo->ostatement); ?>
		
	</div>
	
						<!-- top right bottom left -->
	<div     style='top: 0; margin: -6em auto 0 auto; width: 40em; height: 24em; background-color: aqua; position: relative; '>
		<div class='t110'>
			<div id='estatement'					   ><?php echo($this->obo->ostatement); ?></div>
			<div id='equestion'  style='display: none'><?php echo($this->obo->oquestion);  ?></div>
		</div>
		
		<div class='tqpar'>
			
			<?php for($i=0; $i < IQTask1Front::ann; $i++) { 
				$name = $this->obo->oqnames[$i];
				$ict  = ' data-iscor="';
				$ict .= $this->obo->corName === $name ? 1 : 0;
				$ict .= '" '
	
				?>
			<div class='tqspecp10' <?php echo($ict); ?> data-isqp='1'>
				<div class='tqspec20' data-isqname='1'  ><?php echo($name);?>
			</div>
			</div>
			<?php }	?>
		</div>
		
		<div class='clicki10' id='eclsc'>Click the screen when you are ready to continue.</div>
	</div>
	<div style='visibility: hidden; margin-top: 2em;' id='ebagain'>
		<button onclick='location.reload();' style='font-size: 130%; '>again</button>
	</div>
</div>
	
</body>
</html>
