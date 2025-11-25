<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    >
<meta name='viewport' content='width=device-width, initial-scale=1.0' >
<title>5-letter Mastermind</title>

<style>
input {
    width: 1.2em;
    font-size: 500%;
    text-align: center;
    font-family: monospace;
}

input:invalid {
    background-color: red; 

}
</style>

<script src='/opt/kwynn/js/utils.js'></script>
<script>
    onDOMLoad(() => {
	const e = qs('[data-i="0"][data-g="0"]');
	e.focus();
    });
    
    function oninputf(e) {
	if (!e.checkValidity()) {
	    e.value = '';
	}
	
	e.value = e.value.toUpperCase();
	const ti = e.tabIndex;
	qs('[tabindex="' + (parseInt(ti) + 1) + '"]').focus();
    }
</script>



</head>
<body>
<table>
<?php
    define('N', 5);
    define('G', 6);

    $t = '';

    $k = 1;

    for ($i=0; $i < G; $i++) {
	$t .= '<tr>' . "\n";
	    for($j=0; $j < N; $j++) {
		$t .= "\t" . '<td><input type="text" size="1" maxlength="1" ';
		$t .= "data-i='$j' data-g='$i'";
		$t .= ' oninput = "oninputf(this);" ';
		$t .= ' pattern="^[a-zA-Z]{1}$" ';
		$t .= " tabindex='$k' ";
		$t .= ' /></td>' . "\n";
		
		$k++;
	    }
	$t .= '</tr>' . "\n";
    }

    echo($t);

?>
</table>
</body>
</html>
