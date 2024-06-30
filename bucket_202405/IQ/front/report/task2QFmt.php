<?php

function gett2QFmt(array $a) : string {
	$t5  = '';
	$t5 .= '<div class="q2r">';
	
	for ($j = 0; $j < 2; $j++) 
	for ($i = 0; $i < 4; $i++)
	{
		
		if ($i === 0 && $j === 1) $t5 .= '<br/>';
		$t5 .= $a[$i][$j];
		
	}
	$t5 .= '</div>';
	
	return $t5;
	
}

