<?php

require_once('/opt/kwynn/kwutils.php');

function getCPUCount() { 
    $r = intval(trim(shell_exec('grep -c processor /proc/cpuinfo')));
    kwas(is_integer($r) && $r >= 1, 'bad CPU count');
    return $r;
}

kwas($argc >= 3, 'need 2 program args');

if ($argc >= 4) {
    $argp = $argv[3];
    if ($argp === '-in') {
	$intoh = file_get_contents('php://stdin');
	$iinfo = strlen($intoh);
    }
    else { $iinfo = $intoh = $argp;     }
}
else {
    $iinfo = $intoh = 'cFEmOWWE';
}



$ps = [
        'memory_cost' => 2 << (intval($argv[1]) - 1),
        'time_cost' => intval($argv[2]),
        'threads' => getCPUCount()
    ];

$b = nanotime();
$h = password_hash($intoh, PASSWORD_ARGON2ID, $ps);  
$e = nanotime();
$ps['h'] = $h;
$ps['ms'] = number_format(($e - $b) / pow(10, 6));
$ps['input_info'] = $iinfo;

var_dump($ps);
