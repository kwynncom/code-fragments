<?php

header('Content-Type: text/plain');

require_once('/opt/kwynn/kwshortu.php');
require_once(__DIR__ . '/config.php');

$t = file_get_contents('php://input');
kwas($t && is_string($t) && strlen($t) <= tmate_config::maxstrlen, 'bad input - tmate server receive'); 
kwas(preg_match(tmate_config::re10, $t, $m), 'did not get valid ssh');

$t = str_replace('[tmate]', "\n[tmate]", $t);
$t .= "\n";
$t .= $_SERVER['REMOTE_ADDR'] . '' . "\n";

$f = tmate_config::fpfx . date('Y-m-d_H_i_s') . '_' . base62(5);

file_put_contents($f, '', FILE_APPEND); // my equivalent of "touch" without worrying about dates
chmod($f,0660);
$fres = file_put_contents($f, $t, FILE_APPEND);

kwas($fres === strlen($t), 'bad write - tmate session info');
echo("\n\n\n\n\n\n\n\n\n\n***********\n" . 'OK - web received ' . $m[0] . "\n*****\n");
