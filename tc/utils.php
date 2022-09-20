<?php

require_once('/opt/kwynn/kwutils.php');

function echoAllJSCSS() {

    $r = $_SERVER['DOCUMENT_ROOT'];
    $s =  dirname($_SERVER['REQUEST_URI']);
    $dbase = $r . '' . $s;
    
    $tys = ['css' => '/^.*\.css$/', 'js' => '/^.*\.js$/'];
    
    $kok = '/opt/kwynn/js/utils.js';
    
    foreach($tys as $ext => $re) 
    {
        $fs = [];
        
        if ($ext === 'js' && is_readable($kok)) $fs[] = $kok;
        
        $fs = kwam($fs, rsearch($dbase, $re ));
   
        foreach($fs as $f) {
            $d = str_replace($r, '', $f);
            if      ($ext === 'css') $t = "<link rel='stylesheet' href='$d' />\n";
            else if ($ext === 'js' ) $t = "<script src='$d'></script>\n";   
            echo($t);
        }

    }
}

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