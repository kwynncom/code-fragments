<?php

define('IQT5N', 2);

function getTask5QHT(array $q, string $htcl5 = '', string $htcl10 = '', string $htcl20 = '') { 
	
	$t  = '';
	$t .= "<div class='$htcl5'>";
	for ($i=0	 ; $i < IQT5N; $i++) {
		$t .= '<div class="' . $htcl10 .'" >' . "\n";
		for ($j=0; $j < IQT5N; $j++) {

			$dom = $q[$i][$j]['i'];

			if ($dom) $sc = '-1'; 
			else	  $sc = '1';

			$tr  = '';
			$tr .= "transform: scaleX($sc) ";

			$rot = $q[$i][$j]['o'];
			$tr .= " rotate({$rot}deg); ";


			$s = " style='$tr' ";

			$t .= '<div class="' . $htcl20 . '" ' . $s;
			$t .= '>';
			$t .= 'R';
			$t .= '</div>' . "\n";
		}
		$t .= '</div>';
	}

	$t .= '</div>' . "\n";
	return $t;
	
}
