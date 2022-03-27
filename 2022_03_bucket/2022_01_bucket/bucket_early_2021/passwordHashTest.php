<?php

die('archival only');

require_once('/opt/kwynn/kwutils.php');

function phTest() {
 
    doOrDie();
    
$pwd = '42Bt4sfevnveT2biCSmc';
$ps = [
	'memory_cost' => 1024 * 64,
	'time_cost' => 20,
	'threads' => 12
    ];

$b = hrtime(1);
$h = password_hash($pwd, PASSWORD_ARGON2ID, $ps);
$e = hrtime(1);

$d = $e - $b; unset($b, $e);
$s = round($d / 1000000000, 2); unset($d);

if (!iscli()) header('Content-Type: text/plain');

echo $s . "\n"; unset($s);
print_r(get_defined_vars());
}

phTest();

function doOrDie() {
    $stopAtR = '2020-06-24 23:59';
    $stopAtTS = strtotime($stopAtR);
    $now = time();
    
    $d = $stopAtTS - $now;
    
    if ($d > 0) return true;
    die('hash test expired');
}

/* AWS t3a.nano results to achieve around 0.4s of runtime and without increasing Apache processes' RAM usage.  As of 2020/06/24
$pwd = '42Bt4sfevnveT2biCSmc';
$ps = [
        'memory_cost' => 1024 * 16,
        'time_cost' => 30,
        'threads' => 2
    ];

$h = password_hash($pwd, PASSWORD_ARGON2ID, $ps);  
["h"]=>
  string(98) "$argon2id$v=19$m=16384,t=30,p=2$NDlMVmt1WnZMN0pORXVkcg$xKsceq6utlq4ap+J+RdYieOHopR/w5Plyiwlea+5SH0"

 * My own local dev machine, same day, my 2017 - 2020 machine:
 * 
 * $ps = [
	'memory_cost' => 1024 * 64,
	'time_cost' => 20,
	'threads' => 12
    ];
 *    [pwd] => 42Bt4sfevnveT2biCSmc
    [ps] => Array
        (
            [memory_cost] => 65536
            [time_cost] => 20
            [threads] => 12
        )

    [h] => $argon2id$v=19$m=65536,t=20,p=12$VWFIQ2FBSHc3RUhyeHRMbA$hl4Tju5o1Msyft2/Nc+PeThogM42bAvtkLVJgObCHFc
) *   *  */
