<?php

echo(number_format(PHP_INT_MIN) . "\n " . number_format(PHP_INT_MAX) . "\n");
$bn = 1000000000;
echo(' ' . number_format((strtotime('2120-06-30') * $bn)) . "\n");