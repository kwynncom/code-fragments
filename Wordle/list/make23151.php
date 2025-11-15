<?php

$t = file_get_contents(__DIR__ . '/2315_rc1_raw.txt');
$t = trim($t);
$t = strtolower($t);
$a = preg_split('/\s/', $t);
file_put_contents(__DIR__ . '/wordle_list_2315_words.txt', implode("\n", $a) . "\n");
$j = json_encode($a, JSON_PRETTY_PRINT);
file_put_contents(__DIR__ . '/wordle_list_2315_words.json', $j);
exit(0);
