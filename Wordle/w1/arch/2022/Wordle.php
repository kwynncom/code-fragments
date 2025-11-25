<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>Wordle algorithm (small test list)</title>

<style>
    body { font-family: sans-serif; }
	input { width: 1.2em; text-align: center; font-family: monospace; }
	:invalid { background-color: lightSalmon; }
</style>

<script src='/opt/kwynn/js/utils.js'></script>

<script src='./Wordle_2309.js'></script>

<script>
	
	class Wordle {
		constructor() { 
			this.w = Wordle_2309;
		}
		
		put(a) {
			for (const p in this.w) this.score(a, p);
		}
		
		
		score(a, b) { // a is the guess
			let s = '';
			for (    let i=0; i < 5; i++) 
				for (let j=0; j < 5; j++) {
					const as = a.substring(i, i + 1);
					const bs = b.substring(j, j + 1);
					if (as === bs) {
						if (i === j) s += 'r';
						else		 s += 'o';
						j = 4; // done analyzing
					} else if (j === 4) s += ' ';
					

				}
			
			return
		}
	}
	
	function rowToS(r) {
		let s = '';
		for (let i=0; i < 5; i++) s += byid('ein' + r + '' + i).value;
		s = s.toLowerCase();
		return s;
	}
	
	var wo = new Wordle();

	onDOMLoad(() => { byid('ein00').focus(); });
	function oninf(e) { 
		const re = new RegExp(/^[A-Za-z]$/);
		if (re.test(e.value)) {
			let c = parseInt(e.dataset.c);
			if (c === 4) return wo.put(rowToS(e.dataset.r));
			const id = 'ein' + e.dataset.r + (c + 1);
			byid(id).focus();
		}
	}
</script>

</head>
<body>
    <table>
        <tbody>
			<?php require_once('frag10.php'); ?>
		</tbody>
    </table>
</body>
</html>

