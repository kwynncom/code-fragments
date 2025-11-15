<?php

define('MAX_SEQ', 1607);
define('MAX_DATE', '2025-11-12');
define('MIN_DATE', '2021-06-19');

require_once('/opt/kwynn/kwutils.php');


$t = file_get_contents('/tmp/w.html'); // https://techwiser.com/all-past-nyt-wordle-answers/

$o = getDOMO($t);

$trs = $o->getElementsByTagName('tr');

function doLine(object $o) : array {
    $ret = [];
    $tds = $o->getElementsByTagName('td');
    if (!$tds) return $ret;
    if (isset($tds->length) && $tds->length !== 3) return $ret;
    $n = intval($tds->item(0)->textContent);
    if ($n < 0 || $n > MAX_SEQ) return $ret;
    try {
	$U = strtotime($tds->item(1)->textContent);
	kwas($U >= strtotime(MIN_DATE) && $U <= strtotime(MAX_DATE));

	$t = trim($tds->item(2)->textContent);
	kwas($t && is_string($t) && preg_match('/^[A-Z]{5}$/', $t));
	$word = $t;

    } catch(Throwable $ex) {
	return $ret;
    }
    
    $ret = [
	'n' => $n,
	'date' => date('Y-m-d', $U),
	'word' => $word

];
    
    return $ret;
}

$res = [];

foreach($trs as $tr) {
    $t = doLine($tr);
    if ($t) $res[] = $t;
}

$j = json_encode($res, JSON_PRETTY_PRINT);
file_put_contents(__DIR__ . '/../list/wordle_solutions_to_2025_11_12_rc.json', $j);


exit(0);