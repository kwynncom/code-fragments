<?php

require_once('midcl.php');

class mid_api {
    
    const binparent = __DIR__ . '/c/';
    const bin	    = self::binparent . 'mid';
    const make      = 'cd ' . self::binparent . ' && sudo bash make.sh';
    
    public static function get($stdout = false) {
	return self::get10();
    }
    
    private static function get10() {
	
	global $argv;
	
	$ex = machine_id::getExisting();
	if ($ex) {
	    var_dump($ex);
	    return $ex;
	}
	
	if (file_exists(self::bin)) exec(self::bin);
	else {
	    $issudo = trim(shell_exec('sudo -n true 2>&1')) === '';
	    if ($issudo) {
		exec(self::make);
		exec(self::bin);
	    }
	}
	
	return machine_id::get(true);
    }
}


if (didCLICallMe(__FILE__)) mid_api::get(1);
