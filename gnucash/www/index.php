<?php

$f = __DIR__ . '/../' . 'do1.php';

echo(shell_exec('php '  . $f .  ' 2>&1 '));




