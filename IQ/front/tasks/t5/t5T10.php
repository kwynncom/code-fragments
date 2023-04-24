<?php

$t = '';
for ($i=0; $i < self::othen; $i++) {
	$t .= '<div class="et5p10">' . "\n";
	for ($j=0; $j < self::othen; $j++) {

		$dom = $this->quaps->q[$i][$j]['i'];

		if ($dom) $sc = '-1'; 
		else	  $sc = '1';

		$tr  = '';
		$tr .= "transform: scaleX($sc) ";


		// transform: rotate(90deg);

		$rot = $this->quaps->q[$i][$j]['o'];
		$tr .= " rotate({$rot}deg); ";


		$s = " style='$tr' ";

		$t .= '<div class="et5p20" ' . $s;
		$t .= '>';
		$t .= 'R';
		$t .= '</div>' . "\n";
	}
	$t .= '</div>';
}
echo($t);