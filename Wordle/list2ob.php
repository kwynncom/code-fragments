<?php

$a10 = json_decode(file_get_contents('./list/wordle_list_2309_words.json'), true);
$a = [];
foreach($a10 as $r) {
	$a[$r] = true;
}
$o = (object)$a;
file_put_contents('./www/Wordle_2309.js', json_encode($o, JSON_PRETTY_PRINT));
