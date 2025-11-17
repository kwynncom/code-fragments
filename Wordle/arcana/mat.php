<?php

require_once(__DIR__ . '/../cli/cmp.php');

$a = json_decode(file_get_contents(__DIR__ . '/../list/wordle_list_2315_words.json'), true);
$n = count($a);
$show = 10000;
$ns = 0;

for($i=0; $i < $n; $i++) 
for($j=0; $j < $n; $j++) {

    if (random_int(1, $show) !== $show) continue;

    $sol = $a[$i];
    $gue = $a[$j];

    if ($sol === 'above' && $gue === 'clone') {
	kwnull();
    }

    $res = cmp($sol, $gue);
    if (true) {
	echo($sol . ' ' . $gue . ' ' . $res . "\n");
	$ns = 0;
    }


}



