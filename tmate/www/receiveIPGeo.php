<?php

require_once(__DIR__ . '/config.php');

class ipinfoioCl implements tmate_config {
	
	const tfile = '/tmp/g';
	public readonly array  $geoa;
	public readonly string $geoj;
	
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
		$t = $this->get();
		$l = strlen($t);
		$this->do20($t);
		// $this->do30();
		return;
		
	}
	
	private function setHash($tin) {
		preg_match(tmate_config::resrw, $tin, $m);	kwas($m[1], 'no ssh session for hash - 1326');
		
		
	}
	
	private function do20(string $tin) {
		$this->setHash($tin);
		preg_match('/\s*(\d+)/', $tin, $m); kwas($m[1], 'no byte count 1308 tmate');
		$bs = $m[1];
		$b = intval($m[1]); unset($m);
		$t = substr($tin, $b + strlen($bs));
		$l = strlen($t);
		$j = trim($t);
		$a = json_decode($j, true); kwas($a, 'GEO IP json did not parse - 1316');
		$this->geoj = $j;
		$this->geoa = $a;
		
		return;
	}
	
	private function get() {
		if (is_readable(self::tfile))
			return tmate_get_vinord(file_get_contents(self::tfile));
		file_put_contents(self::tfile, '');
		kwas(chmod(self::tfile, 0600), 'tmate geo chmod 1251 fail');
		$t = tmate_get_vinord();
		file_put_contents(self::tfile, $t);
		return $t;
	}

}

new ipinfoioCl();
