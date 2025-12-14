<?php

class shCmdCl {

    const advicmdConst = 'advi';

    public function dosh(string $which) : mixed {
	switch($which) {
	    case self::advicmdConst : return $this->advi(); break;
	}
    }

    private function advi() : mixed {

	$c = 'adb devices 2>&1';
	belg($c, true);
	$s = shell_exec($c);
	return $s;
    }

}