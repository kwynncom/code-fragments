<?php

require_once('cmp.php');

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