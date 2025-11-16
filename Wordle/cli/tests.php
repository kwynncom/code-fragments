<?php

require_once('utils.php');

try {
    $ts = [
	['lurid' => 'arise'],
	['tinge' => 'arise'],
	['tinge' => 'wound'],
	['youth' => 'arise'],
	['clung' => 'plumb'],
	['freed' => 'ether'], // This caught a bug.
	['quill' => 'arise'],
	['eefgh' => 'abcee'],
	['abcee' => 'eexyz'],
	['yeyey' => 'exexe'],
	['exexe' => 'yeyey'],
	['ebcdx' => 'neeee'], // This also caught a bug.
	['eeedx' => 'neeee'], // caught a bug
	['neeee' => 'eeexx'], // caught a bug, I think.
	['quill' => 'quill'],
];

    foreach($ts as $ts => $t) {
	$word  = key($t);
	$guess = reset($t);
	echo($word . ' ' . $guess . ' ' . cmp($word, $guess) . "\n");
    }

} catch(Throwable $ex) {
    throw $ex;
}
