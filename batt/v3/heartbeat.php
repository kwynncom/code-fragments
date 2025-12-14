<?php

class battLogHBCl {
    public static function noop() {
	static $prev = 0;
	$now = microtime(true);
	if ($now - $prev < 0.3) return;
	belg('.');
	$prev = $now;
    }
}
