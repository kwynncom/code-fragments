<?php 

require_once('utils.php');
require_once(__DIR__ . '/../dat/t1Q.php');

class IQTask1Back extends IQTask1Questions {
	
	public readonly object $quaps;
	
	private readonly string $ostatement;
	private readonly array  $oqnames;
	private readonly string $oquestion;
	private readonly string $corName;
	private readonly array $t20;
	const clgn = 2;
	const clqf = __DIR__ . '/../dat/t1Q.txt';

	private function org10() {
		$a['q0'] = $this->ostatement;
		$a['q'] = $this->oquestion;
		$a['opts'] = $this->oqnames;
		$a['correctAnswer'] = $this->corName;
		putQ($a);
		$this->quaps = (object) $a;
	}
	
	private function pickLevs(array $ain) {
		$ra = [];
		$cki = 0;
		$ta = $ain;
		do { 
			$ra[] = $ti = random_int(0, count($ta) - 1);
			$ta = kwifs($ta, $ti);
			if (!is_array($ta)) break;
		} while($cki++ < 7);
		
		return $ra;
	}
	
	public function __construct() {
		$this->do10();
		$this->do15();
		$this->do20();
		$this->do30();
		$this->do25();
		$this->do40();
		$this->org10();
	
	}
	
	private function do15() {
		$t = file_get_contents(self::clqf);
		$a10 = explode("\n", $t);
		$i10 = 0;
		
		$ra10 = [];
		foreach($a10 as $t20) {
			$t = trim($t20); unset($t20);
			if (!$t) continue;
			if (preg_match('/[;\/]/', $t)) continue; 
			$a20 = preg_split('/\s+/', $t); unset($t); kwas(count($a20) === 4, 'bad trait count - task 1 - 2106');
			$ra10[$i10][0][0] = $a20[0];
			$ra10[$i10][0][1] = $a20[1];
			$ra10[$i10][1][0] = $a20[2];
			$ra10[$i10][1][1] = $a20[3];
			$i10++;
			continue;
		}

		$this->t20 = $ra10;
		
		return;
	}
	
	private function do25() {
		$a = $this->oname;
		$ra = [];
		$ra[] = retAndElim($a);
		$ra[] = retAndElim($a);
		$this->oqnames = $ra;
	}
	
	private function do40() {
		$rel = $this->oaa[1];
		$ai = $rel === $this->oqi ? 0 : 1;
		$this->corName = $cornm = $this->oname[$ai];
		$as = [];
		$as[] = $this->ostatement;
		$as[] = $this->oquestion;
		$as[] = $cornm;
		if (iscli()) foreach($as as $s) echo($s . "\n");
	}
	
	private function do30() {
		$ri = random_int(0, count($this->t20[$this->oaa[0]]) - 1);
		$this->oqi = $ri;
		$qadj = $this->t20[$this->oaa[0]][$ri][0];
		$t  = '';
		$t .= 'Who is ' . $qadj . '?';
		$this->oquestion = $t;
		return;
		
	}
	
	private function do20() {
		$aa  = $this->pickLevs($this->t20);
		$adj = $this->t20[$aa[0]][$aa[1]][$aa[2]];
		$t   = '';
		$t  .= $this->oname[0];
		$t  .= ' is ';
		$t  .= $aa[2] === 0 ? $adj . ' than ' : 'not as ' . $adj . ' as ';
		$t  .= $this->oname[1];
		$t  .= '.';
		
		$this->ostatement = $t;
		$this->oaa = $aa;
		
		return;
	}
	
	private function do10() {
		
		$p0a = $this->pickLevs(self::names);
		$p0nm = self::names[$p0a[0]][$p0a[1]];
		$ta   = self::names[$p0a[0]];
		unset($ta[$p0a[1]]);
		$ta = array_values($ta);
		$p1a = $this->pickLevs($ta);
		$p1nm = $ta[$p1a[0]];
		$na = [];
		$na[0] = $p0nm;
		$na[1] = $p1nm;
		$this->oname = $na;
		return;

	}
}

if (didCLICallMe(__FILE__)) new IQTask1Back();