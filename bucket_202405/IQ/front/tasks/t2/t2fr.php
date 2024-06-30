<?php

require_once(__DIR__ . '/../common.php');

class IQTask2Front extends IQTaskFront {
	
	const clrows = 2;
	const clcols = 4;
	
	public function __construct() {
		$this->loadBack();
		$this->do20();
	}
	
	private function do20() {
		require_once('t2T.php');
	}
	
	public static function lserif(string $l) : string {
		if ($l !== 'l' && $l !== 'I') return $l;
		$t  = '';
		$t .= '<span style="font-family:   Brush Script MT; ">';
		$t .= $l;
		$t .= '</span>';
		return $t;
		
	}
}

new IQTask2Front();