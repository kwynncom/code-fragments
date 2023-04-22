<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>IQ Tasks</title>

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
	
	.tqspec {
		background-color: blue;
		width : 12em;
		height:  5em;
		margin-right: 2em; 
		display: inline-block;
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
	
</style>
</head>
<body>
	
<div style='text-align: center; '>
						<!-- top right bottom left -->
	<div     style='margin: 5em auto 0 auto; width: 40em; height: 25em; background-color: aqua; position: relative; '>
		<div class='t110' style=''>	<?php echo($this->obo->ostatement); ?></div>
		
		<div class='tqpar'>
			<div class='tqspec'></div>
			<div class='tqspec'></div>
		</div>
		
		<div class='clicki10'>Click the screen when you are ready to continue.</div>
	</div>
</div>
	
</body>
</html>




