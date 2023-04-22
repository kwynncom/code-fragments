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
	
	
	public function __construct() {
		$this->do10();
	}
	
	private function do10() {
		$ac = self::nms;
		$a1 = $this->pick1($ac);
		$a2 = $this->pick1($ac, $a1['sexi'], $a1['pi']);
		 
		 return;
	}
	
	private function pick1(array $a, int $sexi = null, int $pi = null) {
		if (isset($sexi)) {
			unset($a[$sexi][$pi]);
			$a = array_values($a);
	} else { 
		$sexi = random_int(0, count($a) - 1);
	}
		 
		 $sexa = $a[$sexi];
		 if (!$sexi) $pi   = random_int(0, count($sexa) - 1);
		 $pnm   =		  $a[$sexi][$pi];
		 return ['name' => $pnm, 'sexi' => $sexi, 'pi' => $pi];
	}
	
}

new iq1();