<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>both IPv4 and v6</title>

<style>
    body { font-family: sans-serif; }
</style>

<?php
	require_once('/opt/kwynn/kwutils.php');

	function getBoth() {
		
		$a = [];
		$a['4']['cmd'] = 'curl --connect-timeout 0.2 http://169.254.169.254/latest/meta-data/public-ipv4 2> /dev/null';
		$a['4']['re' ] = '/^((\d+){1,3}\.){3}(\d+){1,3}$/';
		$a['6']['cmd'] = 'ip -6 addr show scope global';
		$a['6']['re' ] = '/([0-9A-Fa-f:]{39})/';

		$ret = [];
		foreach($a as $k => $v) {
			$cr = shell_exec($v['cmd']);
			if ($cr) $cr = trim($cr);
			if ($cr) {
				preg_match($v['re'], $cr, $ms); unset($cr);
				$r20 = kwifs($ms, 1); unset($ms);
			} else $r20 = '';
			if ($r20) $ip = $r20;
			else	  $ip = ''; unset($r20);
			
			// ${'ipv' . $k} = $ip;	
			$ret[$k] = $ip;
			unset($ip);
			
		} unset($a, $k, $v);
		
		// return get_defined_vars();
		return $ret;
	}
	
	$ips = getBoth();

	if (ispkwd()) { $ips['4'] = '127.0.0.1'; $ips['6'] = '::1'; }
	
	kwynn();
	
?>

<script>
	
	
<?php
	foreach($ips as $k => $v) 
		echo("var ipv$k = " . $ips[$k] . ';' . "\n");

?>
	
	
</script>

</head>
<body>
	<p>default IP: <?php echo(kwifs($_SERVER, 'REMOTE_ADDR')); ?></p>
	<p>v4: </p>
	<p>v6: </p>
	<p>user agent: <?php echo(kwifs($_SERVER, 'HTTP_USER_AGENT')); ?></p>
</body>
</html>
