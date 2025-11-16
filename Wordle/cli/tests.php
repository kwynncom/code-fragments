<?php

require_once('utils.php');

try {
    $ts = [
	['lurid' => 'arise'],
	['tinge' => 'arise'],
	['tinge' => 'wound'],
	['youth' => 'arise'],
	['clung' => 'plumb']
];

    foreach($ts as $ts => $t) {
	$word  = key($t);
	$guess = reset($t);
	echo($word . ' ' . $guess . ' ' . cmp($word, $guess) . "\n");
    }

} catch(Throwable $ex) {
    throw $ex;
}
