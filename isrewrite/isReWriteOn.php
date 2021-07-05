<?php
class apacheModuleCheck {
	
	const defMod = 'mod_rewrite';
	const actKey = 'ApacheModEnCk';
	const allowOthers = false;
	const testThisFileUntil = '2021/07/02 03:00 America/New_York';
	
	public function __construct($fin) { 
		if (!$this->thisFileOK ($fin)) return; unset($fin);
		$this->init10(); 		
		$this->cki(); 	
	}
	
	private function thisFileOK($fin) {
		if ($fin !== __FILE__) return true;
		$d = time() - strtotime(self::testThisFileUntil);
		if ($d < 0) return true;
		return false;
	}
	
	public static function isABeforeB() {
	
	}

	private function init10() {
		if (!self::rqe(self::actKey)) return;
		
		$mod = false;
		if (self::allowOthers) $mod = self::rqe('mod');
		if (!$mod)			   $mod = self::defMod;
		
		$this->mod = $mod; unset($mod);
		if (self::rqe('echo'))  $this->doEc = true;
		else					$this->doEc = false;
		if (self::rqe('ordie')) $this->ordie = true;
		else					$this->ordie = false;
		// if (self::rqe('textHeaders')) $this->txthe = true;
		// else						  $this->txthe = false;

	}

	private function cki() {
		
		if (!isset($this->mod)) return;

		$moin = $this->mod;
		
		$hukey = 'Apache ' . $moin;

		if (!self::bool($moin)) {
			if ($this->doEc) {
				header('Content-Type: text/plain');
				echo($hukey . ' is not active.');
				if ($this->ordie) echo('  Dying...');
			}
			if ($this->ordie) {
				http_response_code(500);
				exit(8284);
			}
		} else if ($this->doEc) {
			header('Content-Type: text/plain');
			echo($hukey . ' is active' . "\n");
		}
	} // https://stackoverflow.com/questions/2069364/how-to-get-phpinfo-variables-from-php-programmatically
	
	public static function rqe($k) {
		$rq = $_REQUEST;
		if (isset(		$rq[$k]))
				return  $rq[$k];
		else 	return false;
	}
	
	public static function bool($moin = self::defMod) {
		return in_array($moin, apache_get_modules());
	
	}
	
//	public static function ck() { new self(); } 
	
}

new apacheModuleCheck(__FILE__);

/* tests:
http://sm20/frag/isrewrite/isReWriteOn.php?XDEBUG_SESSION_START=netbeans-xdebug&ApacheModEnCk=1&echo=1
http://sm20/frag/isrewrite/isReWriteOn.php?XDEBUG_SESSION_START=netbeans-xdebug&ApacheModEnCk=1&echo=1&mod=mod_watchdog
http://sm20/frag/isrewrite/isReWriteOn.php?XDEBUG_SESSION_START=netbeans-xdebug&ApacheModEnCk=1&echo=1&ordie=1

 */