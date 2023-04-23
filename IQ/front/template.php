<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>IQ test</title>

<style>
    body { font-family: sans-serif; }
	li   { margin-bottom: 1em; }

</style>
</head>
<body>
	<p><a href='../'>my IQ home</a></p>
	<section>
		<h2>Tasks</h2>
		
		<ol>
			<?php	require_once(__DIR__ . '/../config.php');
					$t = '';
					for($i=1; $i <= IQTestIntf::tasksn; $i++) {
						$t .= "<li><a href='./loadTask.php?n=$i'>Task " . $i . '</a></li>' . "\n";
					}
					
					echo($t);
					
				?>
			
		</ol>
	</section>
</body>
</html>
