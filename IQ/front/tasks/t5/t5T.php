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
		margin: 2em auto 0 auto;
		bottom: 2em;

		width: 12em;
		height: 1.5em; 
		background-color: green;
	
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
		
	</div>

</div>

</body>
</html>
