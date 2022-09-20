<?php

require_once('/opt/kwynn/kwutils.php');

function docss() {

    $r = $_SERVER['DOCUMENT_ROOT'];
    $s =  dirname($_SERVER['REQUEST_URI']);
    $d = $r . '' . $s;
    
    $fs = rsearch($d, '/^.*\.css$/');
    foreach($fs as $f) {
        $d = str_replace($r, '', $f);
        $t = "<link rel='stylesheet' href='$d' />\n";
        echo($t);
    }
}

docss();

function rsearch($dir, $re) {
    $return = [];
    $iti = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    while($iti->valid()){
        $p = $iti->key();
        if (preg_match($re, $p, $ms)) {
            $return[] = $ms[0];
        }
        $iti->next();
    }
    return $return;
}