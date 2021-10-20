<?php

require_once('/opt/kwynn/kwutils.php');
require_once('/opt/kwynn/isKwGoo.php');

class personal_www {
	
	const fn = 'personal.html';
	const fileloc  = '/var/' . self::fn;
	const filelive = __DIR__ . '/../../../../../' . self::fn;
	
	public function __construct() {	$this->do10();	}
	private function do10() {
		$isa = isKwGoo()|| isKwDev();
		if (!$isa) {echo('not auth'); exit(0); }
		else $this->do20();
	}
	
	private function do20() {
		$f = $this->getFile();
		if (!is_readable($f)) {
			echo('auth but no file');
			exit(0);
		} else echo(file_get_contents($f));
	}
	
	private function getFile() {
		if (isAWS()) return self::filelive;
		else		 return self::fileloc;
	}
	
	
	
}

new personal_www();
