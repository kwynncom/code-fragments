<?php

require_once('/opt/kwynn/kwutils.php');

class moon extends dao_generic_3 { 
	
	const dbname = 'moon';
	
	public static function get() {
		$o = new self();
		return $o->getI();
	}
	
	public function getI() {
		$ra = ['cala' => $this->cala, 'phcha' => $this->phcha];
		$j  = json_encode($ra);
		return $j;
	}
	
	public function __construct() {
		parent::__construct(self::dbname);
		$this->creTabs(['m' => 'moon']);
		$this->mcoll->createIndex(['U' => 1], ['unique' => true]);
		$this->phca = [];
		$this->do10();
		$r30 = $this->do30();
		$this->do40($r30);
		return;
		
	}
	
	function cl10(&$ar) {
		
		$ak  = ['n', 't', 'pd', 'isq'];
		$pkt = ['z', 'U', 'r', 'hut', 'hud'];
		$pk = kwam($ak, $pkt); unset($pkt);
		
		$tar = $ar;
		
		foreach($tar as $f => $ignore) {
			if ($tar['pd'] !== 1) { if (!in_array($f, $ak)) unset($ar[$f]); }
			else  {					if (!in_array($f, $pk)) unset($ar[$f]); 
				$ar['hut'] = date('g:i A', $ar['U']);	
				$ar['ms' ] = $ar['U'] * 1000;
			}
		}
	
		return $ar;
	}
	
	static function hud($din) { return $din->format('D M d'); }
	
	function do40($ala) {

		static $minMax =  10 * 86400;
		
		$d10 = new DateTime();
		$d10->setTimestamp($ala[0]['U']);
		$d20 = new DateTime($d10->format('Y-m-d 23:59:59.999999')); unset($d10);
		$now = time();
		
		
		for ($i=1; $i <= 55; $i++) {
			
			$d20ts = $d20->getTimestamp();
			
			if ($d20ts > $ala[0]['U']) {
				$ta = array_shift($ala);
				$ta['pd'] = 1;
				$this->cl10($ta); 
				$d = $now - $ta['U'];
				if (   ($d > 0 && $d <  $minMax) 
					|| ($d < 0 && $d > -$minMax) )
					$this->phcha[] = $ta; unset($d);
			} else {
				$ta['pd']++;
				$this->cl10($ta);
			}

			$hud = $ta['hud'] = self::hud($d20);
			if ($d20ts >= $now) $r[] = $ta; unset($d20ts);
			$d20->add(new DateInterval('P1D'));
			continue;
		} unset($ta, $d20, $i, $ala, $now, $minMax, $d20ts);
		
		$this->cala = $r; unset($r);
		
		return;
	}
	
	function do30() {
		$now = time();
		$min = $now - 86400 *  9;
		$max = $now + 86400 * 60;
		$res = $this->mcoll->find(['$and' => [['U' => ['$gte' => $min]], ['U' => ['$lt' => $max]]]]);
		return $res;
	}
	
	function already() {
		$now = time();
		if (!$this->mcoll->findOne(['U' => ['$lte' => $now - 86400 * 32]])) return false;
		if (!$this->mcoll->findOne(['U' => ['$gte' => $now + 86400 * 65]])) return false;
		return true;
	}
	
	function do10() {
		if ($this->already()) return;
		return $this->do20(trim(shell_exec('/usr/bin/python3 ' . __DIR__ . '/moon.py')));
	}
	
	function do20($t) {
		$aa = explode("\n", $t); unset($t);
		kwas(count($aa) === 3, 'moon bad count 0020');
		$ms = [];
		foreach([0,1] as $i) preg_match_all("/'([^']+)'/", $aa[$i], $ms[$i]); unset($i);
		$a['z'] = $ms[0][1];
		$a['t'] = $ms[1][1];
		preg_match_all('/\d/', $aa[2], $ms[2]); unset($aa);
		$a['n'] = $ms[2][0]; unset($ms);
		$r = [];
		foreach($a['z'] as $i => $z) {
			$U = strtotime($z);
			$n  = intval($a['n'][$i]);
			$t = $a['t'][$i];
			$r = date('r', $U);
			$_id =  $z . '-mph-' . $n;
			$dat = get_defined_vars(); 
			unset($dat['a'], $dat['i']);
			$this->mcoll->upsert(['_id' => $_id], $dat, 1, false); unset($U, $dat, $n, $r, $t, $z, $_id);
		} 	unset($i, $p, $a);
	}
	
	public static function ppyarr($s) {
		return str_replace("'", '"', $s) . ';';
		
	}
}

new moon();