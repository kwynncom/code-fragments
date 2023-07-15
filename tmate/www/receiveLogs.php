<?php

header('Content-Type: text/plain');

require_once(__DIR__ . '/config.php');

$t = tmate_get_vinord();
$t .= "\n"; // This is needed to separate tmate and geo info; then it needs to be removed
$t .= $_SERVER['REMOTE_ADDR'] . '' . "\n";

// note that I am making assumptions about this format on the display / show side
mkdir_safe(tmate_config::sessdir);
$f = tmate_config::fpfx . tmate_config::metafn .  date('Y-m-d_Hi_s') . '_' . base62(tmate_config::sfxn) . tmate_config::sfx;

file_put_contents($f, '', FILE_APPEND); // my equivalent of "touch" without worrying about dates
chmod($f,tmate_config::permf);
$fres = file_put_contents($f, $t, FILE_APPEND);

kwas($fres === strlen($t), 'bad write - tmate session info');
echo("\n\n\n\n\n\n\n\n\n\n***********\n" . 'OK - web received tmate info' . "\n*****\n");
