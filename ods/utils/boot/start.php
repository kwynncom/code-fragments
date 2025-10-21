<?php

require_once   (__DIR__ . '/../config.php');


function hoursBootStartF() {
    $dir = realpath(__DIR__ . '/../../');

    $c = 'php -S ' . hoursIntf::host . ':' . hoursIntf::port . ' -t ' . $dir;

    shell_exec('pkill -x -f ' . '"' . $c . '"');
    shell_exec($c);
}

hoursBootStartF();
