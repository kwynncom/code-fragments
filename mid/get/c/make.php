<?php

$cmd = 'sudo php ' . __DIR__ . '/../midcl.php';

$c = <<<CPROG10
// THIS ENTIRE PROGRAM IS WRITTEN FROM PHP; DON'T CHANGE THE C FILE
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
