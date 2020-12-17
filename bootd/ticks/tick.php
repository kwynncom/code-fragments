<?php

require_once('/opt/kwynn/kwutils.php');



$limitS = 20;
$startS = time();
$fn = __DIR__ . '/res/' . 'ticks_start_' . $starS . '.txt';

$bn = pow(10,9);

$b = nanopk();
$i=0;
do {
    usleep(50000 * $i);
    $n = nanopk();
    if ($i <= 1) { $i++; continue; }
    $dns = $n['Uns' ] - $b['Uns'];
    $dtk = $n['tick'] - $b['tick'];
    $r = $dns / $dtk;
    if (!isset($pr)) { $pr = $r; continue; }
    $d = abs($r - $pr);
    $ds = $dns / $bn;
    $s = '';
    $s .= sprintf('%0.14f', $r);
    $s .= ' ';
    $s .= sprintf('%0.14f', $d);
    $s .= ' ';
    $s .= intval($ds);
    $s .= ' ';
    $s .= "\n";
    echo($s);
    kwas(file_put_contents($fn, $s, FILE_APPEND) === strlen($s), 'write fail');
    $pr = $r;
    
    $i++;
}  while(time() - $startS < $limitS);

exit(0);
