<?php

require_once('/opt/kwynn/kwutils.php');

$n = 50000;

echo(dao_generic_3::get_oids() . "\n");
for($i=0; $i < $n; $i++) dao_generic_3::get_oids();
echo(dao_generic_3::get_oids() . "\n");
