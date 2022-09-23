<?php

require_once('config.php');

$all = json_decode(file_get_contents(WordleConfig::listJSON), 1); kwas(count($all) === WordleConfig::count, 'bad file count');

$lc = [];
for ($i=0; $i < 26; $i++) $lc[chr(ord('a') + $i)] = 0;

$tck = 0;
foreach($all as $w) for($i=0; $i < WordleConfig::wordLength; $i++) {
    $lc[$w[$i]]++;
    $tck++;
}

kwas($tck === WordleConfig::wordLength * WordleConfig::count, 'bad total check');

$tv = 0;
foreach(['a', 'e', 'i', 'o', 'u'] as $v) {
    echo($v . ' ' . $lc[$v] . "\n");
    $tv += $lc[$v];
}

echo($tv . "\n");
echo($tv / WordleConfig::count . "\n");

arsort($lc);
print_r($lc);
exit(0);


