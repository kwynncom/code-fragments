<?php

function retAndElim(array &$a) : int | string {
	$si = random_int(0, count($a) - 1);
	$sel = $a[$si];
	unset($a[$si]);
	$a = array_values($a);
	return $sel;
}