<?php

// nohup java -Dpidfile=$pidfile $jopts $mainClass </dev/null > $logfile 2>&1

shell_exec('nohup sleep 20 < /dev/null > /dev/null 2>&1  &');
