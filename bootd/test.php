<?php

require_once('boot.php');

if (1) boot_tracker::getID();
else {

$k = ftok(boot_tracker::myFile, 'm');
$shma = shm_attach($k, 500, 0644);
$dat = shm_get_var($shma, 1);
exit(0);
}