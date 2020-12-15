<?php

require_once('midClass.php');

class mid_api {
    
    const binparent = __DIR__ . '/c/';
    const bin	    = self::binparent . 'mid';
    const make      = 'cd ' . self::binparent . ' && sudo bash make.sh';
    
    public static function get($stdout = false) {
	self::get10();
    }
    
    private static function get10() {
	
	global $argv;
	
	if (file_exists(self::bin)) { exec(self::bin); exit(0); }
	else if (true || (isset($argv[1]) && $argv[1] === '-make')) {
	    $issudo = trim(shell_exec('sudo -n true 2>&1')) === '';
	    if ($issudo) {
		exec(self::make);
		exec(self::bin);
		exit(0);
	    }
	}
	
	machine_id::get(true);
    }
}


if (didCLICallMe(__FILE__)) mid_api::get(1);
