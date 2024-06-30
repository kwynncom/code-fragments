<?php

require_once(__DIR__ . '/../config.php');

$t = file_get_contents('php://input');
kwas($t && is_string($t) && strlen($t) <= tmate_config::maxstrlen, 'bad input - tmate server receive - IP'); 

echo('ip hi');
