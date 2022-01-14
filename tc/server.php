<?php

require_once('/opt/kwynn/kwutils.php');

class timecard {
	public function __construct() { $this->do10(); }
	public function do10() {
		if (isrv('start')) {
			kwynn();
		}
	}
}

new timecard();