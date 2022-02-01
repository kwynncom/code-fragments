<?php

require_once('/opt/kwynn/kwutils.php');

class moon extends dao_generic_3 { 
	
	const dbname = 'moon';
	
	public function __construct() {
		parent::__construct(self::dbname);
		$this->creTabs(['m' => 'moon']);
		$this->mcoll->createIndex(['U' => 1], ['unique' => true]);
		$this->do10();
		$r30 = $this->do30();
		$this->do40($r30);
		return;
		
	}
	
	function cl10(&$ar) {
		
		$ak  = ['n', 't', 'pd', 'isq'];
		$pkt = ['z', 'U', 'r'];
		$pk = kwam($ak, $pkt); unset($pkt);
		
		$tar = $ar;
		
		foreach($tar as $f => $ignore) {
			if ($tar['pd'] !== 1) { if (!in_array($f, $ak)) unset($ar[$f]); }
			else				  { if (!in_array($f, $pk)) unset($ar[$f]); }
		}
	
		return $ar;
	}
	
	function do40($ala) {

		$d10 = new DateTime();
		$d10->setTimestamp($ala[0]['U']);
		$d20 = new DateTime($d10->format('Y-m-d 23:59:59.999999')); unset($d10);
		
		for ($i=1; $i <= 9; $i++) {
			if ($d20->getTimestamp() > $ala[0]['U']) {
				$ta = array_shift($ala);
				$ta['pd'] = 1;
				$this->phca[] = $this->cl10($ta);
			} else {
				$ta['pd']++;
				$this->cl10($ta);
			}

			$r[$d20->format('D M d')] = $ta;
			$d20->add(new DateInterval('P1D'));
			continue;
		} unset($ta, $d20, $i, $ala);
		
		return;
		

			
			
		
	}
	
	function do30() {
		$now = time();
		$min = $now - 86400 *  9;
		$max = $now + 86400 * 50;
		$res = $this->mcoll->find(['$and' => [['U' => ['$gte' => $min]], ['U' => ['$lt' => $max]]]]);
		return $res;
	}
	
	function already() {
		$now = time();
		if (!$this->mcoll->findOne(['U' => ['$lte' => $now - 86400 * 32]])) return false;
		if (!$this->mcoll->findOne(['U' => ['$gte' => $now + 86400 * 55]])) return false;
		return true;
	}
	
	function do10() {
		if ($this->already()) return;
		return $this->do20(trim(shell_exec('python3 ' . __DIR__ . '/moon.py')));
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