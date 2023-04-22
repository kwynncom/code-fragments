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
		
		$ap[] = $p1a = $this->pickPer(self::nms);
		$ap[] =		   $this->pickPer(self::nms, $p1a['sexi'], $p1a['pi']); unset($p1a);
		
		$aa[] = $a1a = $this->pickPer(self::trs);
		$aa[] =		   $this->pickPer(self::trs, $a1a['sexi'], $a1a['pi']);
				
		return;
	}
	
	private function pickPer(array $a, int $sexi = null, int $pi = null) {
		if (isset($sexi)) {
			unset($a[$sexi][$pi]);
			$sexa = array_values($a[$sexi]);

	
		} else {
			$sexi = random_int(0, count($a) - 1);
			$sexa = $a[$sexi]; 
		}
		

				
		 $pi   = random_int(0, count($sexa) - 1);
		 $pnm   =		  $sexa[$pi];
		 return ['name' => $pnm, 'sexi' => $sexi, 'pi' => $pi];
	}
	
}

new iq1();