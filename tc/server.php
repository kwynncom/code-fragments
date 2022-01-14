<?php

require_once('/opt/kwynn/kwutils.php');

class timecard {
public function __construct() { $this->do10(); }
public function do10() {
	if (isrv('start')) {
		$now = time();
		$r = [];
		$r['U' ]  = date('U'	, $now);
		$r['db']  = date('Y-m-d', $now);
		$r['hr']  = date('H:i'  , $now);
		$r['dow'] = date('D'	, $now);
		kwjae($r);
	} // if
} // func
} // class

new timecard();