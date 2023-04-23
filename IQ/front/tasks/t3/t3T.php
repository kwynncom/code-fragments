<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>IQ Task 3</title>

<script src='/opt/kwynn/js/utils.js'></script>
<script src='tasks/t3/t3.js'></script>

<link rel='stylesheet' href='common.css'>

<script>
	var KWIQT3A = '<?php echo($this->obo->oanswer . ''); ?>';
</script>

<style>
	
	.parent {
		text-align: center; 
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		font-family: Helvetica ;
		width: 40vw;
	}
	
	.eans {
			/* top right bottom left */
		
		display: inline-block; 
		width:  4.7em;
		height: 2.5em; 

		margin: auto 0.3em auto 0.3em;
		background-color:   #3366ff;
		color: white;
		font-size: 175%;
		font-weight: bold;
		
		vertical-align: middle; 
		position: relative;

		
	}
	
	.eansI20 {
		display: inline-block;
		top: 50%;
		left: 50%;

		transform: translate(-50%, -50%);
		position: absolute;
		
	}

	body { width: 90vw; height: 97vw; }

</style>
</head>
<body>
	
<div>
	<div class='thomasOuterColor' style='top: 0; margin: 2em auto 0 auto; width: 40vw; height: 10em; position: relative; '>
		<div class='parent' >
			<?php for ($i=0; $i < count($this->obo->olist); $i++) { 
				$num = $this->obo->olist[$i];
				$t = '';
				$t .= '<div class="eans" onclick="t3inter.onclick(this);" data-iamn="' . $num . '" >';
				$t .= '<div class="eansI20" >';
				$t .= $num;
				$t .= '</div>';
				$t .= '</div>';				
				echo($t);
			} ?>
			
		</div>
	</div>
</div>
</body>
</html>
