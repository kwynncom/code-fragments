<?php

require_once('/opt/kwynn/kwutils.php');

function docss() {
    $fs = glob(__DIR__ . '/*.css');
    foreach($fs as $f) ftou($f);
}

docss();

function ftou($f) {
    $r = $_SERVER['DOCUMENT_ROOT'];
    if ($l = readlink($r)) $r = $l;
    $p10 = str_replace($r, '', $f);
    $up = $_SERVER['REQUEST_URI'];
    $s =  $_SERVER['PHP_SELF'];
    $d = dirname($s);
    $fd = str_replace($r, '', $f);
    
    return;
}