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
		kwjss.sobf('server.php', { min : byid('minie').value, max : byid('maxie').value});
	}
}
</script>
	

<style>
    body		{ font-family: sans-serif; }
	label.l10	{ font-family: monospace; }
	input.mm	{ font-family: monospace; width: 11ch; }
	.resp10     { height: 5em; }
</style>
</head>
<body>
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
		<pre class='resp10'>

		</pre>
    </section>
	<div>
		<p><a href='/'>home</a></p>
	</div>
</body>
</html>
