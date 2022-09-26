<?php

require_once('/opt/kwynn/kwutils.php');

if (0) {
for($i=0; $i < 1000; $i++) {
$t = shell_exec(__DIR__ . '/../../ntpc/sntpkw2 -nosleep -noqck');
cout($t);

}
}
else {
for ($i=0; $i < 1000; $i++) {
$poke = fopen('/var/kwynn/mysd/poke', 'w');
fwrite($poke, 'a', 1);
fclose($poke);
$get  = fopen('/var/kwynn/mysd/get' , 'r');
$s = '';
if (1) for ($j=0; $j < 5; $j++) {
	$s .= fgets($get);
	
}
cout($s);
fclose($get);
}
}
function cout($t) {
	$a = explode("\n", $t);
	kwas($a[4] === '::1', 'not local IP!!! - sntp check');
	echo(number_format( $a[2] - $a[1]) . "");
	// t = ((  T2  -   T1) +    (T3  -   T4)) / 2. // RFC SNTP ; 1 starting offset
	$d =   (($a[1] - $a[0]) + ($a[2] - $a[3])) / 2;
	echo(' ' . number_format($d) . "\n");	
}
