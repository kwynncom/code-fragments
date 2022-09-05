<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>check my timeserver</title>

<style>
    body { font-family: sans-serif; }
	.pre10 { 
		font-size: 4vw;
		margin-top: 0;
		margin-bottom: 0;
		display: inline-block;
		
	}
	
	.pre20 { 
		font-size: 2.5vw; 
		margin-top	 : 0.4ex;
		margin-bottom: 1ex;
		
		
	}
</style>
</head>
<body>
	<div style='position: relative; '>
	<pre class='pre10'><!--
		--><?php require_once('tsck.php');	
				 $o = new stsck();
			?><!--
	--></pre>
	<div style='display: inline-block; position: absolute; top: 1ex; margin-left: 2ex; '>
		<button onclick='history.go(0);'><span style='font-size: 3vw;'>&#10227;</span></button>
	</div>
	</div>
	<pre class='pre20'><!--
		--><?php echo($o->getCmdsS()); ?><!--
	--></pre>
	<div>
		<p>testing <a href='https://github.com/kwynncom/simple-time-server'>
				my simple timeserver</a></p>
		<p><a href='https://github.com/kwynncom/code-fragments/tree/master/tsck'>this testing app's source code</a> (subject to change)</p>
		<p><a href='https://github.com/kwynncom/code-fragments/tree/a87f9fead64cfd77d3eebc9f6b068389508581df/tsck'>a specific version of the code</a>
			
		</p>
		<p><a href='/t/20/08/dates/'>PHP date format codes</a></p>
		<p><a href='/'>home</a></p>
		
		<p>Note to self: this will test that the server is working, but it does NOT test outside accessibility.  I'm almost certain I have my server 
			wired to translate kwynn.com to the loopback (127.0.0.1).
			
		</p>
		
		<p>Note 2: /etc/hosts modified:</p> 
		<p>::1 ip6-localhost ip6-loopback kwynn.com</p>
		<p>To make sure IPv6 works.</p>
			
		
		
	</div>
</body>
</html>

