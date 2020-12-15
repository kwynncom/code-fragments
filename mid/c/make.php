<?php

$cmd = 'sudo php ' . __DIR__ . '/../midClass.php';

$c = <<<CPROG10
#include <stdlib.h>
#include <unistd.h>
void main()
{
	setreuid(geteuid(), getuid());
	system("$cmd");
	
}
CPROG10;


$cpp = __DIR__ . '/mid.c';
file_put_contents($cpp, $c);
