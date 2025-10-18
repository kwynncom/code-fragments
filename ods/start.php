<?php

$c = 'php -S localhost:8000 -t ' . __DIR__;

shell_exec('pkill -x -f ' . '"' . $c . '"');
shell_exec($c);
