<?php // https://www.thomas.co/sites/default/files/thomas-files/2022-09/Aptitude%20Example%20Booklet_2021%20V1.pdf

class iq1 {
	
	const nms  = [
			['Rachel', 'Wendy'],
			['Fred', 'John', 'Pete', 'Tom', 'Bob', 'Paul']
		];
	                          /* not as */
	const trs  = [	[['lighter','heavy'   ], ['heavier' , 'light']],
					[['duller', 'bright'  ], ['brighter', 'dull']],
					[['sadder', 'happy'   ], ['happier' , 'sad']], 
					[['weaker', 'strong'  ], ['stronger', 'weak']],
					[['sadder', 'happy'   ], ['happier' , 'sad']]
		];
	
	/* private function pickAdj() {
		$li = 0;
		$la = self::trs;
		$t = '';
		$ca = [];
		
		for($i=0; $i < count($la); $i++) {
			$ti = random_int(0, count($la) - 1);
			$ia[] = $ti;
			$la = $la[$ti];
		}
		
		return;
	} */
	
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
		
		$ap[] = $p1a = $this->pickLevs(self::nms);
		$ap[] =		   $this->pickLevs(self::nms, $p1a); unset($p1a);
		
		$aa[] = $a1a = $this->pickLevs(self::trs);
		$aa[] =		   $this->pickLevs(self::trs, $a1a); unset($a1a);
		
		$p1 = self::nms[$ap[0][0]][$ap[0][1]];
		$p2 = self::nms[$ap[1][0]][$ap[1][1]];
		
		echo($p1 . ' ' . $p2);
	}
	
	private function pickLevs(array $a, array|null $la = []) {
		
		$pa = [];
		
		if (isset(   $la[0])) {
			$a0 = $a[$la[0]];
			for($i = 0; $i < count($a0); $i++) $pa[$i] = $i;
			unset($pa[$la[1]]);
			$pa = array_values($pa);
			$la[1] = $pa[random_int(0, count($pa) - 1)];
			return $la;
				
		} else {
			$la[0] = random_int(0, count($a) - 1);
			$la[1] = random_int(0, count($a[$la[0]]) - 1);

		}
		 
		return $la;
	}
	
}

new iq1();