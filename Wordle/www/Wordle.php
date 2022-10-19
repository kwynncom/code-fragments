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
	
	// class 
	
	function ws(a, b) {
		let s = [];
		for (    let i=0; i < 5; i++) 
			for (let j=i; j < 5; j++) {
				const as = a.substring(i, 1);
				const bs = b.substring(j, 1);
				if (as !== bs) { s[i] = ' '; continue; }
				if (i === j) s[i] = 'r';
				else		 s[i] = 'o';
			}
		
	}
	
	onDOMLoad(() => { byid('ein00').focus(); });
	function oninf(e) { 
		const re = new RegExp(/^[A-Za-z]$/);
		if (re.test(e.value)) {
			const id = 'ein' + e.dataset.r + (parseInt(e.dataset.c) + 1);
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

