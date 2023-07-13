<?php

require_once(__DIR__ . '/config.php');

class ipinfoioCl implements tmate_config {
	
	const tfile = '/tmp/g';
	const defperm = 0660;
	
	public readonly array  $geoa;
	public readonly string $geoj;
	public readonly string $ohash;
	
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
		$t = $this->get();
		$l = strlen($t);
		$this->do20($t);
		$this->do30();
		return;
		
	}
	
	private function do30() {
		$a = $this->geoa;
		$this->valGA($a);
		$ip = $a['ip'];
		
		$f = tmate_get_fn(self::ipaddir, 0, $a);
		
		$j = $this->geoj;
		kwtouch($f, $j, self::defperm);
		kwtouch(self::hashdir . $this->ohash, $j, self::defperm);
	}
	
	private function setHash($tin) {
		$this->ohash = tmate_get_hash($tin);
	}
	
	private function valGA(array $a) {
		kwas($a['ip'], 'ip does not exist - tmate geo 1419');
		kwas(getValidIPOrFalsey($a['ip']), 'bad IP tmate geo 1420');
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
		$this->valGA($a);
		$this->geoj = $j;
		$this->geoa = $a;
		
		return;
	}
	
	private function get() {
		if (0 && is_readable(self::tfile)) // ***KDKJ!*!&*!*!!*
			return tmate_get_vinord(file_get_contents(self::tfile));
		file_put_contents(self::tfile, '');
		kwas(chmod(self::tfile, 0660), 'tmate geo chmod 1251 fail');
		$t = tmate_get_vinord();
		file_put_contents(self::tfile, $t);
		return $t;
	}

}

new ipinfoioCl();
