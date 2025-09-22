<?php

require_once('get.php');
require_once('out.php');

$dat = adbCl::get();
new adbDisplayCl($dat); 
unset($dat);
