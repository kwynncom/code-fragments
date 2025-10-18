<?php

require_once(__DIR__ . '/../utils/ods.php');

class odsDo10Cl {

    public readonly string $ht;

    private function __construct() {

	$o = new odsFirstSheetCl();
	$this->do10($o->hours);
	
    }

    private function do10(array $ain) {
	uasort($ain, function($a, $b) { return $a['earnedTo'] <=> $b['earnedTo']; });
	$a = $ain; unset($ain);
	ob_start();
	require_once('T10.php');
	$ht = ob_get_clean();
	return $this->ht = $ht;
    }

    public static function getHT() : string {
	$o = new self();
	return $o->ht;
    }
}

