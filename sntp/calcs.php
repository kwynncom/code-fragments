<?php

require_once('get.php');
require_once('/opt/kwynn/kwcod.php');

$iter = 3;
$geto = new ntpQuotaGet();
$ras = $geto->get($iter);
