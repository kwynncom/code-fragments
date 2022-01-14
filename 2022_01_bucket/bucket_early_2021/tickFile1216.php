<?php

require_once('/opt/kwynn/kwutils.php');

$limitS = pow(10,8);
$startS = time();
$fn = __DIR__ . '/res/' . 'ticks_start_' . $startS . '.txt';

$bn = pow(10,9);
$precision = '%0.16f';
$dofile = true;

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
    $s .= sprintf($precision, $r);
    $s .= ' ';
    $s .= sprintf($precision, $d);
    $s .= ' ';
    $s .= intval($ds);
    $s .= ' ';
    $s .= "\n";
    echo($s);
    if ($dofile) kwas(file_put_contents($fn, $s, FILE_APPEND) === strlen($s), 'write fail');
    $pr = $r;
    
    $i++;
}  while(time() - $startS < $limitS);

exit(0);
