<?php

$pid = pcntl_fork();
if ($pid == 0) file_put_contents('/var/kwynn/mysd/poke', 'a');
