<?php

require_once('/opt/kwynn/kwutils.php');

function getCPUCount() { 
    $r = intval(trim(shell_exec('grep -c processor /proc/cpuinfo')));
    kwas(is_integer($r) && $r >= 1, 'bad CPU count');
    return $r;
}

kwas($argc >= 3, 'need 2 program args');

if ($argc >= 4) $pwd = $argv[3];
else		$pwd = 'cFEmOWWE';



$ps = [
        'memory_cost' => 2 << (intval($argv[1]) - 1),
        'time_cost' => intval($argv[2]),
        'threads' => getCPUCount()
    ];

$b = nanotime();
$h = password_hash($pwd, PASSWORD_ARGON2ID, $ps);  
$e = nanotime();
$ps['h'] = $h;
$ps['ms'] = number_format(($e - $b) / pow(10, 6));
$ps['pwd'] = $pwd;

var_dump($ps);
