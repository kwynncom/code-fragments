<?php

$i = 0;

do {
	file_put_contents('/tmp/e', date('r') . "\n", FILE_APPEND);
	sleep(5);
} while ($i++ < 100);
