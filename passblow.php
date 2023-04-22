<?php

require_once('/opt/kwynn/kwutils.php');

$pwd = base62(20);
echo($pwd . "\n");
echo(password_hash($pwd,  PASSWORD_BCRYPT) . "\n");
