<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>random integer</title>

<script src='/opt/kwynn/js/utils.js'></script>
<script>
class doit {
	constructor() {
		kwjss.sobf('server.php', { min : byid('minie').value, max : byid('maxie').value}, this.onret, false);
	}
	
	onret(resraw) {
		const o = JSON.parse(resraw);
		inht('rawres', resraw);
		inht('rmin'  , o.minnf);
		inht('rmax'  , o.maxnf);
		inht('rrand' , o.rand );
	}
}

onDOMLoad(() => { new doit(); });

</script>
	

<style>
    body		{ font-family: sans-serif; }
	label.l10	{ font-family: monospace; }
	input.mm	{ font-family: monospace; width: 11ch; }
	.resp10     { height: 5em; }
	.retlab     { display: inline-block; width: 1.2in; }
	#rmin, #rrand, #rmax { 
		display: inline-block; 
		width  : 1in;
		text-align: right; 
		/* background-color: blue;  */
	}
	
	#rrand, .randlab { 
		font-weight: bold;
		font-size  : 130%; 
	}
</style>
</head>
<body>
	<div>
		<p><a href='/'>home</a></p>
	</div>

    <section>
        <h1>random integer</h1>
		
		<?php $pmin = PHP_INT_MIN; $pmax = PHP_INT_MAX; ?>
       
		<div style='position: relative; '> <!-- mm doit -->
		<div style='display: inline-block; '> <!-- min max -->
			<div>
				<label class='l10'>min</label>
				<input type='number' step='1' min='<?php echo($pmin); ?>' max='<?php echo($pmax); ?>' value='1'  id='minie' class='mm' />
			</div>
			<div style='margin-top: 1ex; '><label class='l10'>max</label>
				<input type='number' step='1' min='<?php echo($pmin); ?>' max='<?php echo($pmax); ?>' value='10' id='maxie' class='mm' />
			</div>
		</div> <!-- min max -->
		<div  style='bottom: 1em; display: inline-block; position: relative; margin-left: 1ex; '>
			<button onclick='new doit();'>doit</button>
		</div>
		</div> <!-- mm doit -->

		<div style='margin-top: 2ex; '>
			<div><label class='retlab'>returned min</label> <span id='rmin' ></span></div>
			<div><label class='retlab randlab'>random</label>		<span id='rrand'></span> </div>
			<div><label class='retlab'>returned max</label> <span id='rmax' ></span></div>
		</div>
		
		
		<pre class='resp10' id='rawres'>

		</pre>
    </section>
</body>
</html>
