<?php

require_once(__DIR__ . '/../sntp/sntp.php');
$p = sntp_get_actual::getClientPacket();
echo($p);
