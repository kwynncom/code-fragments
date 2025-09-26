<?php

require_once(__DIR__ . '/get.php');
require_once(__DIR__ . '/out.php');

$dat = adbCl::get();
new adbDisplayCl($dat); 
unset($dat);
