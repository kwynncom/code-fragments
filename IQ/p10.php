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
		 $sexi = random_int(0, count(self::nms) - 1);
		 $sexa = self::nms[$sexi];
		 $p1i   = random_int(0, count($sexa) - 1);
		 $p1n   = self::nms[$sexi][$p1i];
		 
		 return;
	}
	
}

new iq1();