<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    >
<meta name='viewport' content='width=device-width, initial-scale=1.0' >
<title>stopwatch</title>

<script src='/opt/kwynn/js/utils.js'></script>

<script>
class kwstopwatch {
    
    setU(U) {
	const min = 1727597589000;
	if (U < min) U *= 1000;
	kwas(U > min, 'timestamp too early');
	this.U = U;
    }
    
    constructor(id, U) {
	this.id = id;
	this.setU(U);
	this.doit();
    }
    
    doit() {
	
	// 227 too fast
	const interval = 269;
	this.doon10();
	this.theintv = setInterval(() => { this.doon10(); }, interval);
    }
    
    doon10() {
	const dms = time() - this.U;
	const ds  = dms / 1000;
	const df  = ds.toFixed(2);
	// const df = df10.r
	inht(this.id, df);
    }
    

}
</script>

</head>
<body>
<div id='out10'>

</div>
<div>
<?php 
    require_once('/opt/kwynn/kwutils.php');
    $now = time();
    echo(date('r', $now)); 
?>
</div>

<script>
    new kwstopwatch('out10', <?php echo($now); ?>);
</script>

</body>
</html>
