<?php

require_once(__DIR__ . '/../cli/cmp.php');

$a = json_decode(file_get_contents(__DIR__ . '/../list/wordle_list_2315_words.json'), true);
$n = count($a);
$show = 500;
$ns = 0;

$ress = [];

for($i=0; $i < $n; $i++) {

    if (random_int(1, $show) !== $show) continue;

for($j=0; $j < $n; $j++) {



    $sol = $a[$i];
    $gue = $a[$j];

    if ($sol === 'above' && $gue === 'clone') {
	kwnull();
    }

    $res = cmp($sol, $gue);
    $ress[$res] = [$sol, $gue];
    if (true) {
	 echo($sol . ' ' . $gue . ' ' . $res . "\n");
	$ns = 0;
    }
}

}

var_dump($ress);
echo(count($ress) . ' results' . "\n");


