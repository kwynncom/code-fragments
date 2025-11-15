<?php

$a2315 = json_decode(file_get_contents(__DIR__ . '/wordle_list_2315_words.json'), true);
$a2309 = json_decode(file_get_contents(__DIR__ . '/wordle_list_2309_words.json'), true);
$d1 = array_diff($a2315, $a2309);
$d2 = array_diff($a2309, $a2315);

$dt = implode(' ', $d1) . "\n";

file_put_contents(__DIR__ . '/6WordDiff.txt', $dt);

exit(0);
