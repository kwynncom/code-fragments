<?php
class apacheModuleCheck {
	
	const defMod = 'mod_rewrite';
	const actKey = 'ApacheModEnCk';
	
	public function __construct() { $this->init10(); 		$this->cki(); 	}

	private function init10() {
		if (!self::rqe(self::actKey)) return;
		$mod = self::rqe('mod');
		if (!$mod) $this->mod = self::defMod;
		else	   $this->mod = $mod;
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

new apacheModuleCheck();

/* tests:
http://sm20/frag/isReWriteOn.php?XDEBUG_SESSION_START=netbeans-xdebug&ApacheModEnCk=1&echo=1
http://sm20/frag/isReWriteOn.php?XDEBUG_SESSION_START=netbeans-xdebug&ApacheModEnCk=1&echo=1&mod=mod_watchdog
http://sm20/frag/isReWriteOn.php?XDEBUG_SESSION_START=netbeans-xdebug&ApacheModEnCk=1&echo=1&ordie=1

 */