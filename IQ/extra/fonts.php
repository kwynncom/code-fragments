<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>fonts</title>

<style>
	
</style>
</head>
<body>
	<?php
		$t = '';
		$fs = ['Apple Chancery', 'Bradley Hand', 'Comic Sans MS', 'Comic Sans', 'monospace', 'serif', 'Arial', 'Brush Script MT', 'Brush Script Std',
			'fantasy', 'Helvetica', 'Verdana', 'Trebuchet MS', 'Times', 'Times New Roman', 'serif', 'Didot', 'Georgia', 'Palatino', 'URW Palladio L', 
			];
		
		$fs = array_reverse($fs);
		
		foreach ($fs as $f) {
			$t .= "<p style='font-family: " . '"' . $f . '"' . "'>" . $f . ": ";
			$t .= 'l';
			$t .= "</p>";
		}
		echo($t);
	?>
</body>
</html>

