<?php

	// $KW_G_SPATH = '/t/22/07/ip46/server.php';
	$KW_G_SPATH = '/server.php';

	require_once('/opt/kwynn/kwutils.php');

	function getBoth() {
		
		global $KW_G_SPATH;
		$p = $KW_G_SPATH;
		
		$a = [];
		$a['4']['cmd'] = 'curl --connect-timeout 0.2 http://169.254.169.254/latest/meta-data/public-ipv4 2> /dev/null';
		$a['4']['re' ] = '/^((\d+){1,3}\.){3}(\d+){1,3}$/';
		
		if (1) {
		$a['6']['cmd'] = 'ip -6 addr show scope global';
		$a['6']['re' ] = '/([0-9A-Fa-f:]{39})/';
		}

		$ret = [];
		foreach($a as $k => $v) {
			$cr = shell_exec($v['cmd']);
			if ($cr) $cr = trim($cr);
			if ($cr) {
				preg_match($v['re'], $cr, $ms); unset($cr);
				$r20 = kwifs($ms, 1); unset($ms);
			} else $r20 = '';
			if ($r20) $ipp = $r20;
			else	  $ipp = ''; unset($r20);
			
			if (ispkwd() && $k == 4) $ipp = '127.0.0.1'; 
			if (ispkwd() && $k == 6) $ipp = '::1'; 

			$is6 = $k == '6';
			$ip  = '';
			$ip .= 'http://';
			$ip .= $is6 ? '[' : '';
			$ip .= $ipp;
			$ip .= $is6 ? ']' : '';
			$ip .= ':19999';
			$ip .= $p;
			
			
			// if ($k != 6) header('Access-Control-Allow-Origin: ' . $ip); 


			$ret[$k] = $ip;
			
			unset($ipp, $ip);
			
		} unset($a, $k, $v);
		
		// return get_defined_vars();
		return $ret;
	}
	
	$ips = getBoth();

// header('Access-Control-Allow-Origin: http://127.0.0.1:19999/server.php?XDEBUG_SESSION_START=netbeans-xdebug');

	
	kwynn();
	
?><!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>both IPv4 and v6</title>

<style>
    body { font-family: sans-serif; }
</style>


<script src='/opt/kwynn/js/utils.js'></script>

<script>
	var KW_G_ips = {};
	
<?php
	foreach($ips as $k => $v) {
		echo("\tKW_G_ips[$k] = {};\n");
		echo("\tKW_G_ips['$k']['srv'] = '" . $ips[$k] . '\';' . "\n");
	}

?>
	const fs = ['4', '6']; // one removed for now
	for (let i=0; i < fs.length; i++) {
		const url = KW_G_ips[fs[i]]['srv']; //  +  '/t/22/07/ip46/server.php';
		kwjss.sobf(url);
	}
	
</script>

</head>
<body>
	<p>default IP: <?php echo(kwifs($_SERVER, 'REMOTE_ADDR')); ?></p>
	<p>v4: <span id='eipv4' /></p>
	<p>v6: <span id='eipv6' /></p>
	<p>user agent: <?php echo(kwifs($_SERVER, 'HTTP_USER_AGENT')); ?></p>
</body>
</html>
