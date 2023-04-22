<?php 

require_once('/opt/kwynn/kwutils.php');

class IQTask1Back {
	
	public readonly string $ostatement;
	
	const names  = [
			['Rachel', 'Wendy'],
			['Fred', 'John', 'Pete', 'Tom', 'Bob', 'Paul']
		];
	                          
	const traits = [		  /* not as */
					[['lighter','heavy'   ], ['heavier' , 'light']],
					[['duller', 'bright'  ], ['brighter', 'dull']],
					[['sadder', 'happy'   ], ['happier' , 'sad']], 
					[['weaker', 'strong'  ], ['stronger', 'weak']],
					[['sadder', 'happy'   ], ['happier' , 'sad']]
		];
	
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
		$this->do20();
		$this->do30();
		$this->do40();
	}
	
	private function do40() {
		$rel = $this->oaa[1];
		$ai = $rel === $this->oqi ? 0 : 1;
		$as = [];
		$as[] = $this->ostatement;
		$as[] = $this->oquestion;
		$as[] = $this->oname[$ai];
		if (iscli()) foreach($as as $s) echo($s . "\n");
	}
	
	private function do30() {
		$ri = random_int(0, count(self::traits[$this->oaa[0]]) - 1);
		$this->oqi = $ri;
		$qadj = self::traits[$this->oaa[0]][$ri][0];
		$t  = '';
		$t .= 'Who is ' . $qadj . '?';
		$this->oquestion = $t;
		return;
		
	}
	
	private function do20() {
		$aa  = $this->pickLevs(self::traits);
		$adj = self::traits[$aa[0]][$aa[1]][$aa[2]];
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
		$this->oname = [];
		$this->oname[0] = $p0nm;
		$this->oname[1] = $p1nm;
		return;

	}
}

if (didCLICallMe(__FILE__)) new IQTask1();