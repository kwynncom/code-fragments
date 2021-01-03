<?php

function getCPUModel() {
    $cmd = 'grep "model name" /proc/cpuinfo | sort -u';
    $r = shell_exec($cmd); unset($cmd);
    preg_match('/model name\s*:\s*(.*)/', $r, $m);
    if (!isset($m[1])) return '';
    $r5 = trim($m[1]);
    $r8 = preg_replace('/\s+/', ' ', $r5);
    return $r8;
    
}


// model name	: Intel(R) Xeon(R) CPU           X5650  @ 2.67GHz

    echo(getCPUModel() . "\n");