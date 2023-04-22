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
		width : 10em;
		height:  5em;
		margin-right: 2em; 
		display: inline-block;
	}
	
	.tqpar { 
		padding-top: 2em; 
		margin-top: 2em; 
		
	}
	
</style>
</head>
<body>
	
<div style='text-align: center; '>
						<!-- top right bottom left -->
	<div     style='margin: 5em auto 0 auto; width: 40em; height: 25em; background-color: aqua'>
		<div class='t110' style=''>	<?php echo($this->obo->ostatement); ?></div>
		
		<div class='tqpar'>
			<div class='tqspec'></div>
			<div class='tqspec'></div>
		</div>
	</div>
</div>
	
</body>
</html>




