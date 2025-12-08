<?php

function beout(string $s) {
    echo($s . "\n");
    $c = 'busctl --user emit /kwynn/batt com.kwynn IamArbitraryNameButNeeded s ' . '"' . $s . '"';
    shell_exec($c);
}

