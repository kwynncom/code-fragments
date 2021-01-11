<?php

require_once('/opt/kwynn/kwutils.php');

class chrony {
    public static function get() {
	
    }
}

function callChrony() {
    global $argv;
    global $argc;
    
    $argv[0] = '/usr/bin/chronyc';
    if ($argc === 1) $argv[1] = 'tracking';
    $cmd = implode(' ', $argv);
    // echo($cmd . "\n");
    $res = shell_exec($cmd);
    echo($res);
}

if (didCLICallMe(__FILE__)) callChrony();
