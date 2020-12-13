<?php

echo('Enter total sales for the month' . "\n");
$sales = trim(fgets(STDIN));
$state = $sales * 0.08;
$county = $sales * 0.035;
echo("state  tax: $state \n");
echo("county tax: $county \n");
$tt = $state + $county;
echo("total tax: $tt \n");
