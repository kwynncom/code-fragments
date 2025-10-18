<?php

require_once('ods.php');

class odsDo10Cl {

    public readonly string $ht;

    private function __construct() {

	$o = new odsFirstSheetCl();
	$this->do10($o->hours);
	
    }

    private function do10(array $ain) {
	uasort($ain, function($a, $b) { return $a['daysAhead'] <=> $b['daysAhead']; });
	$a = $ain; unset($ain);

	require_once('T10.php');

	// foreach($ain as $p) $this->do20($p);

	return $this->ht = '';
    }

    public static function getHT() : string {
	$o = new self();
	
	return $o->ht;
    }
}

