<?php

require_once('midcl.php');

class machine_id {
    
    const binparent = __DIR__ . '/c/';
    const bin	    = self::binparent . 'mid';
    const make      = 'cd ' . self::binparent . ' && sudo bash make.sh';
    
    public static function get($showstdout = false) {
	self::clean();
	return self::get10($showstdout);
    }
    
    private static function clean() {
	global $argv;
	if (!in_array('-clean', $argv)) return;
	if (file_exists(self::bin)) unlink(self::bin);
	machine_id_internal::rm();
    }
    
    private static function get10($showstdout) {
	
	global $argv;
	
	$ret = machine_id_internal::getExisting();

	if (!$ret) {
	    if (file_exists(self::bin)) exec(self::bin);
	    else {
		$issudo = trim(shell_exec('sudo -n true 2>&1')) === '';
		if ($issudo) {
		    exec(self::make);
		    exec(self::bin);
		}
	    }

	    $ret = machine_id_internal::get(true);	    
	}
	
	if ($showstdout) var_dump($ret);
	return $ret;

    }
}


if (didCLICallMe(__FILE__)) machine_id::get(1);
