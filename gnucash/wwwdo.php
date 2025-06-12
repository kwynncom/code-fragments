<?php

require_once('/opt/kwynn/kwutils.php');

class wwwdo {

    private static function secure() {
	kwas(ispkwd(), 'can only be run from my server (err #02458)');
    }

    public static function callFromWeb() {

	$cmd = 'sudo -u ' . get1000UserName() . ' /usr/bin/php ' . realpath(__FILE__) . ' 2>&1';
	echo(shell_exec($cmd));
	unset($cmd);
    }

    public static function runGNUCash() {
	self::secure();
	require_once('do1.php');
        new balancesCl('html');
    }
}

if (didCLICallMe(__FILE__)) wwwdo::runGNUCash();
else			    wwwdo::callFromWeb();
