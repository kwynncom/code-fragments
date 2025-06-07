<?php

require_once('fileGet.php');

do10();

function do10() {

    $o = new xactsGetCl();

    $arev = array_reverse($o->currXacts);
    var_dump($arev); unset($arev);
    echo($o->balStart . "\n");

    unset($o);

}

