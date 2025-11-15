<?php

define('F',   5);
define('Y', '1');
define('N', '0');
define('M', '+');
define('Z', ' ');

function isl(string $l) : bool { return preg_match('/^[a-z]{1}$/', $l) ? true : false; }

function cmp(string $a, string $b) : string {

    $r = '?????';

    for ($i=0; $i < F; $i++) {
	if ($a[$i] !== $b[$i]) continue;
	$r[$i] = Y;
	$a[$i] = $b[$i] = Z;
    }

    for ($i=0; $i < F; $i++)
    for ($j=0; $j < F; $j++) {
	if ($i === $j)			continue;
	if (!isl($a[$i]))		continue;
	if (!isl($b[$j]))		continue;
	if (	 $a[$i] !== $b[$j])	continue;
	$r[$j] = M;
	$a[$i] = $b[$j] = Z;
    }

    for ($i=0; $i < F; $i++) if ($r[$i] === '?') $r[$i] = N;

    return $r;

}

function getPossible(string $g, string $f) : array {

    static $ws = [];

    if (!$ws) { $ws = json_decode(file_get_contents(__DIR__ . '/../list/wordle_list_2309_words.json'), true); }

    $ret = [];
    foreach($ws as $w) {
	if (cmp($w, $g) === $f) $ret[] = $w;
    }
    return $ret;
}

function getPossibleCulm(array $fs) : array {
    $ret = [];

    foreach($fs as $f) {
	$t = getPossible(key($f), reset($f));
	if ($ret) $ret = array_intersect($t, $ret);
	else      $ret = $t;
    }

    $ret = array_values($ret);

    return $ret;
}