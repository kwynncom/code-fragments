<?php

require_once(__DIR__ . '/../utils/ods.php');
require_once(__DIR__ . '/../utils/arr.php');

class odsDo10Cl {

    public readonly string $ht;

    private function __construct() {

	$araw = odsDoCl::get();
	$a = odsFirstSheetCl::getCalcs($araw);
	$this->do10($a);
	// exit(0); // @(@(@(@(@(
	
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

