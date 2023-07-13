<?php

require_once(__DIR__ . '/config.php');

class ipinfoioCl {
	
	const tfile = '/tmp/g';

	public function __construct() {
		$this->do10();
	}
	
	public function do10() {
		$t = $this->get();
		$l = strlen($t);
		$this->do20($t);
		return;
		
	}
	
	public function do20(string $tin) {
		preg_match('/\s*(\d+)/', $tin, $m); kwas($m[1], 'no byte count 1308 tmate');
		$bs = $m[1];
		$b = intval($m[1]); unset($m);
		$t = substr($tin, $b + strlen($bs));
		$l = strlen($t);
		$j = trim($t);
		$a = json_decode($j, true);
		
		return;
	}
	
	public function get() {
		if (is_readable(self::tfile))
			return file_get_contents(self::tfile);
		file_put_contents(self::tfile, '');
		kwas(chmod(self::tfile, 0600), 'tmate geo chmod 1251 fail');
		$t = tmate_get_vinord();
		file_put_contents(self::tfile, $t);
		return $t;
	}

}

new ipinfoioCl();
