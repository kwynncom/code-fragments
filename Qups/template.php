<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>Q drop update tracker</title>

<?php 
		require_once('btof.php'); 
		$GTHEO = new qanonBackToFrontClass();

?>

<script>
	var KW_QUPS_ASOF_MS = <?php echo($GTHEO->getLastAsofMS()); ?>;
</script>

<style>
body { 
	font-family: sans-serif; 
	font-size: 80%;
}

td.tdlen1 {
	font-size: 250%;
	font-weight: bold;
}

#asof10 {
	font-family: monospace;
	font-size: 180%;
	
}
</style>

<script src='/opt/kwynn/js/utils.js'></script>

<script>
	function setAsof() {
		try { 
			const asof = KW_QUPS_ASOF_MS;
			if (asof <= 0) kwas(false, 'bad ts 2342');
			const now = time();

			const dr = (now - asof) / 1000;
			let d = dr;
			if (d < 0) d = 0.1;
			
			const di10 = d.toFixed(1);

			byid('asof10').innerHTML = di10;
		} catch(ex) {
			cl('set inverval dying with ' + ex);
			clearInterval(KW_QUPS_IV);
		}
		
	}
	
	var KW_QUPS_IV = false; 
	onDOMLoad(
		() => { 
			const f = setAsof;
			f();
			KW_QUPS_IV = setInterval(f, 170); 
		}
	);

	
</script>

</head>
<body>
	<div>
		<div    style='display: inline-block; font-size: 150%; '><a href='https://qanon.pub/'>Q</a></div>
		<button style='display: inline-block; margin-left: 10ex; ' onclick='history.go(0);'>reload</button>
		<div    style='display: inline-block;'>more info at bottom</div>
	
	</div>
       
       <p><?php echo($GTHEO->getMeta()); ?></p>

	   	
	<p><span id='asof10'>---</span>s ago (source checked)</p>
	   
       <table>
               <thead>
                       <tr>
                               <th>len</th>
						   <th>etag</th>
						   <th>lm</th>
                               <th>asof</th>


                       </tr>
               </thead>
               <tbody>
                       <?php echo($GTHEO->getHTRows()); ?>
               </tbody>
       </table>
       
	   <div>
		   <p><a href='https://github.com/kwynncom/code-fragments/tree/master/Qups'>source code for now</a></p>

		   <p><a href='https://github.com/kwynncom/code-fragments/tree/c31ec64639f658dcc41930485986489cd43654e5/Qups'>source code of a specific 
				   version</a> (probably old)</p>
   
		   <p><a href='/'>Kwynn's home</a>
			   
		   </p>
	   </div>
	   
</body>
</html>
