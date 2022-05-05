<?php

require_once('/opt/kwynn/kwutils.php');

class GNUCashParse {
	
	public function __construct() {
		$this->load();
		$this->p10();
	}
	
	private function load() {
		
		global $argv;
		
		$path = $argv[1];
		$gz = file_get_contents($path . 'gnucash.xml.gnucash'); unset($path);
		$x  = gzdecode($gz); unset($gz);
		$o =  XMLReader::XML($x); unset($x);
		$this->theo = $o;		
	}

	private function p10() {
		
		$o = $this->theo;

		for($i=0; $i < 100000; $i++) {
			echo($o->depth . ' ' . $o->name . ' ' . "\n");
			$o->read();
		}
	}
}


new GNUCashParse();