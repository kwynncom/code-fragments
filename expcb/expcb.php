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
		kwjss.sobf('expcbServer.php', {}, recvbtc);
	}
	
	function recvbtc() {
		cl('hi');
	}
</script>

</head>
<body>
	<div>
		<button onclick='getbtc();'>reload</button>
	</div>
</body>
</html>

