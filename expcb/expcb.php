<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>BitCoin price with backoff</title>

<style>
    body { font-family: sans-serif; }
</style>

<script src='/opt/kwynn/js/utils.js'></script>

<script>
	function getbtc() {
		kwjss.sobf('expcbServer.php', {}, recvbtc, false);
	}
	
	function recvbtc(r) {
		byid('pricee').innerHTML = r;
	}
	
	getbtc();
</script>

</head>
<body>
	<div>
		<button onclick='getbtc();' style='font-size: 130%'>reload</button>
	</div>
	<div>
		<p>This price of BitCoin is $<span id='pricee'></span></p>
		<p><span id='mybme'></span> relative to my personal benchmark</p>
	</div>
</body>
</html>

