<?php

$a = json_decode(file_get_contents('./bulkData/a1.json'), true);
arsort($a);
file_put_contents('./bulkData/a2.json', json_encode($a, JSON_PRETTY_PRINT));
exit(0);
