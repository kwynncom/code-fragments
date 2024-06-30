<?php

function e($s) { echo($s); }

for($i=0; $i < 1; $i++) {
	e('<tr>');	
	for($j=0; $j < 5; $j++) {
		e('<td>');			
		$s  = '';
		$s .= '<input type="text" size="1" maxlength="1" '; 
		$id = 'ein' . $i . $j;
		$s .= "id='$id' ";
		$s .= " data-r='$i' data-c='$j' ";
		$s .= " pattern='^[A-Za-z]$' ";
		$s .= ' oninput="oninf(this);" ';
		$s .= ' >';
		echo($s);
		e('</td>');
	}
	e('</tr>' . "\n");	
}
