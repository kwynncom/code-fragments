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
<body onclick='t1inter.bodyClick();'>
	
<div style='text-align: center; '>
						<!-- top right bottom left -->
	<div     style='margin: 5em auto 0 auto; width: 40em; height: 24em; background-color: aqua; position: relative; '>
		<div class='t110'>
			<span id='estatement'					   ><?php echo($this->obo->ostatement); ?></span>
			<span id='equestion'  style='display: none'><?php echo($this->obo->oquestion);  ?></span>
		</div>
		
		<div class='tqpar'>
			<div class='tqspecp10'><div class='tqspec20' data-qname='1'><?php echo($this->obo->oqnames[0]);?></div></div>
			<div class='tqspecp10'><div class='tqspec20' data-qname='1'><?php echo($this->obo->oqnames[1]);?></div></div>
		</div>
		
		<div class='clicki10'>Click the screen when you are ready to continue.</div>
	</div>
</div>
	
</body>
</html>
